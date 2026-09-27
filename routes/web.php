<?php
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\Project;
use App\Models\TeamRequest;
use App\Models\ChatMessage;

Route::get('/', function () {
    $stats = [
        'active_users' => User::whereNotNull('email_verified_at')->where('team_status', 'active')->count(),
        'projects' => Project::where('status', 'ongoing')->count(),
        'study_programs' => User::whereNotNull('study_program')
            ->where('study_program', '!=', '')
            ->distinct()
            ->count('study_program'),
    ];
    return view('v_hero.hero', compact('stats'));
})->name('home');
Route::view('/login', 'v_login.login')->name('login');
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);
    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
    }
    $request->session()->regenerate();
    return redirect()->intended(route('home'));
})->name('login.store');
Route::view('/register', 'v_login.register')->name('register');
Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
        'password' => ['required', 'confirmed', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/'],
    ]);
    $email = strtolower(trim($data['email']));
    $existing = User::where('email', $email)->first();
    if ($existing?->hasVerifiedEmail()) {
        return back()->withErrors(['email' => 'Email sudah digunakan.'])->withInput($request->except('password', 'password_confirmation'));
    }
    $user = $existing ?: new User();
    $user->fill([
        'name' => $data['name'],
        'email' => $email,
        'password' => Hash::make($data['password']),
    ]);
    $user->save();
    if (filter_var(env('VERIFY_EMAIL', false), FILTER_VALIDATE_BOOL)) {
        try {
            event(new Registered($user));
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withErrors(['email' => 'Akun tersimpan, tetapi email verifikasi gagal dikirim. Periksa konfigurasi SMTP.'])->withInput($request->except('password', 'password_confirmation'));
        }
    }
    if (! filter_var(env('VERIFY_EMAIL', false), FILTER_VALIDATE_BOOL)) {
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('complete-profile');
    }
    Auth::logout();
    $request->session()->regenerate();
    return redirect()->route('login')->with('success', 'Akun dibuat. Cek email untuk verifikasi sebelum masuk.');
})->name('register.store');
Route::get('/email/verify', function () {
    return view('v_login.verify-email');
})->middleware('auth')->name('verification.notice');
Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->intended(route('home'));
    }
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Tautan verifikasi baru sudah dikirim.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);
    abort_unless(URL::hasValidSignature($request), 403);
    abort_unless(hash_equals(sha1($user->getEmailForVerification()), $hash), 403);
    if (! $user->hasVerifiedEmail()) $user->markEmailAsVerified();
    return redirect()->route('login')->with('success', 'Email berhasil diverifikasi. Silakan masuk.');
})->middleware('signed')->name('verification.verify');
/*
|--------------------------------------------------------------------------
| KELENGKAPAN PROFIL AWAL & EDIT KEAHLIAN
|--------------------------------------------------------------------------
*/
$renderSkillsView = function (Request $request, bool $editingSkills = false) {
    $availableSkills = [
        'UI/UX Design',
        'Web Development',
        'Mobile Development',
        'Data Analysis',
        'Backend Development',
        'Frontend Development',
        'Graphic Design',
        'Project Management',
        'Quality Assurance',
    ];
    return view('v_login.complete-profile', [
        'availableSkills' => $availableSkills,
        'userSkills' => $request->user()->skills ?? [],
        'editingSkills' => $editingSkills,
    ]);
};
Route::get('/complete-profile', function (Request $request) use ($renderSkillsView) {
    return $renderSkillsView($request, false);
})->middleware('auth')->name('complete-profile');
Route::get('/profile/skills', function (Request $request) use ($renderSkillsView) {
    return $renderSkillsView($request, true);
})->middleware('auth')->name('profile.skills.edit');
Route::post('/complete-profile', function (Request $request) {
    $data = $request->validate([
        'skills' => ['nullable', 'array'],
        'skills.*' => ['string', 'max:100'],
        'other_skill' => ['nullable', 'string', 'max:100'],
    ]);
    $skills = $data['skills'] ?? [];
    if (! empty($data['other_skill'])) {
        $skills[] = trim($data['other_skill']);
    }
    $skills = array_values(
        array_unique(
            array_filter(
                array_map(
                    fn ($skill) => is_string($skill) ? trim($skill) : $skill,
                    $skills
                ),
                fn ($skill) => filled($skill)
            )
        )
    );
    $request->user()->update([
        'skills' => $skills,
    ]);
    /*
    | Jika dibuka dari Edit Profil, kembali ke halaman Edit Profil.
    */
    if ($request->boolean('from_profile')) {
        return redirect()
            ->route('profile.edit')
            ->with('success', 'Keahlian berhasil diperbarui!');
    }
    /*
    | Jika ini kelengkapan profil setelah registrasi,
    | logout lalu arahkan ke halaman login.
    */
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()
        ->route('login')
        ->with('success', 'Profil selesai! Silakan masuk ke akun kamu.');
})->middleware('auth')->name('complete-profile.store');
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->middleware('auth')->name('logout');
Route::view('/tentang-kami', 'v_tentang-kami.tentang-kami')->name('about');
Route::get('/jelajahi-projek', function (Request $request) {
    $search = trim((string) $request->query('q', ''));
    $skills = collect($request->user()?->skills ?? [])
        ->filter(fn ($skill) => is_string($skill) && trim($skill) !== '')
        ->map(fn ($skill) => strtolower(trim($skill)))
        ->unique()
        ->values();
    $usesSkillRecommendations = $skills->isNotEmpty();
    $projects = Project::query()
        ->with(['owner', 'members'])
        ->where('status', 'ongoing')
        ->latest()
        ->get();
    if ($usesSkillRecommendations) {
        $projects = $projects
            ->map(function ($project) use ($skills) {
                $matches = collect($project->roles ?? [])
                    ->pluck('name')
                    ->filter(fn ($role) => is_string($role))
                    ->map(fn ($role) => strtolower(trim($role)))
                    ->intersect($skills)
                    ->count();
                return ['project' => $project, 'matches' => $matches];
            })
            ->filter(fn ($item) => $item['matches'] > 0)
            ->sortByDesc('matches')
            ->pluck('project')
            ->values();
    }
    if ($search !== '') {
        $needle = strtolower($search);
        $projects = $projects->filter(function ($project) use ($needle) {
            return str_contains(strtolower($project->title), $needle)
                || str_contains(strtolower($project->description), $needle)
                || str_contains(strtolower($project->owner?->name ?? ''), $needle)
                || collect($project->roles ?? [])->pluck('name')->filter(fn ($role) => is_string($role))
                    ->contains(fn ($role) => str_contains(strtolower($role), $needle));
        })->values();
    }
    $applications = $request->user()
        ? TeamRequest::where('sender_id', $request->user()->id)
            ->whereIn('project_id', $projects->pluck('id'))
            ->get()
            ->keyBy('project_id')
        : collect();
    return view('v_explore.index', compact('projects', 'search', 'usesSkillRecommendations', 'applications'));
})->name('explore');
Route::get('/explore/detail', function () {
    return redirect()->route('explore');
});
Route::get('/explore/detail/{project}', function (Request $request, Project $project) {
    $project->load(['owner', 'members']);
    $application = $request->user()
        ? TeamRequest::where('sender_id', $request->user()->id)
            ->where('project_id', $project->id)
            ->first()
        : null;
    return view('v_explore.detail', compact('project', 'application'));
})->name('explore.detail');
// ========================================
// DETAIL PROJEK
// ========================================
Route::get('/jelajahi-projek/{project}', function (Project $project) {
    $project->load(['owner', 'members']);
    return view('v_explore.project', compact('project'));
})->name('explore.project');
// ========================================
// AJUKAN PERMINTAAN BERGABUNG
// ========================================
Route::post('/projek-saya/{project}/gabung', function (
    \Illuminate\Http\Request $request,
    \App\Models\Project $project
) {
    $userId = $request->user()->id;
    $message = \Illuminate\Support\Facades\DB::transaction(
        function () use ($project, $userId) {
            $project = \App\Models\Project::query()
                ->whereKey($project->id)
                ->lockForUpdate()
                ->firstOrFail();
            if ((int) $project->owner_id === (int) $userId) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'join' => 'Kamu adalah pemilik projek ini.',
                ]);
            }
            if ($project->members()->whereKey($userId)->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'join' => 'Kamu sudah menjadi anggota projek ini.',
                ]);
            }
            if ($project->status !== 'ongoing') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'join' => 'Projek ini sudah tidak menerima permintaan.',
                ]);
            }
            $application = \App\Models\TeamRequest::firstOrCreate(
                [
                    'sender_id' => $userId,
                    'recipient_id' => $project->owner_id,
                    'project_id' => $project->id,
                ],
                [
                    'status' => 'pending',
                    'message' => 'Mengajukan permintaan bergabung.',
                ]
            );
            return match ($application->status) {
                'pending' => 'Permintaan terkirim. Menunggu persetujuan pemilik projek.',
                'rejected' => 'Permintaan bergabungmu sebelumnya telah ditolak.',
                default => 'Permintaan ini sudah diproses oleh pemilik projek.',
            };
        }
    );
    return redirect()
        ->route('explore.application', $project)
        ->with('success', $message);
})->middleware('auth')->name('projects.join');
Route::get('/jelajahi-projek/{project}/status', function (Request $request, Project $project) {
    if ($project->members()->whereKey($request->user()->id)->exists()) {
        return redirect()->route('projects.workspace', $project);
    }

    $application = TeamRequest::where('sender_id', $request->user()->id)
        ->where('project_id', $project->id)
        ->firstOrFail();

    if ($application->status === 'accepted') {
        return redirect()->route('projects.workspace', $project);
    }

    return view('v_explore.application', compact('project', 'application'));
})->middleware('auth')->name('explore.application');
/*
|--------------------------------------------------------------------------
| PEMILIK MENERIMA / MENOLAK PERMINTAAN BERGABUNG
|--------------------------------------------------------------------------
*/
Route::post(
    '/projek-saya/{project}/permintaan/{teamRequest}/{decision}',
    function (
        Request $request,
        Project $project,
        TeamRequest $teamRequest,
        string $decision
    ) {
        \Illuminate\Support\Facades\DB::transaction(
            function () use (
                $request,
                $project,
                $teamRequest,
                $decision
            ) {
                /*
                |--------------------------------------------------------------------------
                | LOCK PROJECT
                |--------------------------------------------------------------------------
                */
                $project = Project::query()
                    ->whereKey($project->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                /*
                |--------------------------------------------------------------------------
                | HANYA OWNER YANG BOLEH MEMPROSES
                |--------------------------------------------------------------------------
                */
                abort_unless(
                    (int) $project->owner_id
                    === (int) $request->user()->id,
                    403
                );
                /*
                |--------------------------------------------------------------------------
                | LOCK PERMINTAAN
                |--------------------------------------------------------------------------
                */
                $application = TeamRequest::query()
                    ->whereKey($teamRequest->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                /*
                |--------------------------------------------------------------------------
                | PASTIKAN PERMINTAAN SESUAI PROJECT
                |--------------------------------------------------------------------------
                */
                abort_unless(
                    (int) $application->project_id
                        === (int) $project->id
                    &&
                    (int) $application->recipient_id
                        === (int) $project->owner_id
                    &&
                    (int) $application->sender_id
                        !== (int) $project->owner_id,
                    404
                );
                /*
                |--------------------------------------------------------------------------
                | HARUS MASIH PENDING
                |--------------------------------------------------------------------------
                */
                if ($application->status !== 'pending') {
                    throw
                    \Illuminate\Validation\ValidationException::withMessages([
                        'approval' =>
                            'Permintaan ini sudah diproses.',
                    ]);
                }
                /*
                |--------------------------------------------------------------------------
                | TERIMA
                |--------------------------------------------------------------------------
                */
                if ($decision === 'terima') {
                    if ($project->status !== 'ongoing') {
                        throw
                        \Illuminate\Validation\ValidationException::withMessages([
                            'approval' =>
                                'Projek sudah tidak menerima anggota baru.',
                        ]);
                    }
                    /*
                    |--------------------------------------------------------------------------
                    | MASUKKAN PELAMAR SEBAGAI ANGGOTA
                    |--------------------------------------------------------------------------
                    */
                    if (
                        ! $project
                            ->members()
                            ->whereKey(
                                $application->sender_id
                            )
                            ->exists()
                    ) {
                        $project
                            ->members()
                            ->syncWithoutDetaching([
                                $application->sender_id => [
                                    'role' => 'Anggota',
                                ],
                            ]);
                    }
                    $application->status =
                        'accepted';
                }
                /*
                |--------------------------------------------------------------------------
                | TOLAK
                |--------------------------------------------------------------------------
                */
                else {
                    $application->status =
                        'rejected';
                }
                /*
                |--------------------------------------------------------------------------
                | TANDAI NOTIFIKASI SUDAH DIBACA
                |--------------------------------------------------------------------------
                */
                $application->read_at =
                    now();
                $application->save();
            }
        );
        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE HALAMAN SEBELUMNYA
        |--------------------------------------------------------------------------
        |
        | Kalau aksi dari halaman notifikasi:
        | kembali ke detail notifikasi.
        |
        | Kalau aksi dari Projek Saya:
        | kembali ke Projek Saya.
        |
        */
        return back()->with(
            'success',
            $decision === 'terima'
                ? 'Permintaan diterima. Anggota berhasil ditambahkan.'
                : 'Permintaan bergabung ditolak.'
        );
    }
)
->middleware('auth')
->where(
    'decision',
    'terima|tolak'
)
->name('projects.requests.decide');
/*
|-------------------------------------------------------------------------
| NOTIFIKASI
|--------------------------------------------------------------------------
*/
Route::get(
    '/notifikasi',
    function (Request $request) {
        $userId =
            $request->user()->id;
        /*
        |--------------------------------------------------------------------------
        | PERMINTAAN JOIN KE PROJECT MILIK USER
        |--------------------------------------------------------------------------
        */
        $notifications =
            TeamRequest::with([
                'sender',
                'project',
            ])
            ->where(
                'recipient_id',
                $userId
            )
            ->where(
                'sender_id',
                '!=',
                $userId
            )
            /*
            |--------------------------------------------------------------------------
            | PASTIKAN INI PERMINTAAN JOIN PROJECT
            |--------------------------------------------------------------------------
            */
            ->whereHas(
                'project',
                function ($query) use ($userId) {
                    $query->where(
                        'owner_id',
                        $userId
                    );
                }
            )
            ->latest()
            ->get();
        /*
        |--------------------------------------------------------------------------
        | JUMLAH BELUM DIBACA
        |--------------------------------------------------------------------------
        */
        $unreadCount =
            $notifications
                ->whereNull('read_at')
                ->count();
        return view(
            'v_notifications.index',
            compact(
                'notifications',
                'unreadCount'
            )
        );
    }
)
->middleware('auth')
->name('notifications.index');
/*
|--------------------------------------------------------------------------
| DETAIL NOTIFIKASI / DETAIL PELAMAR
|--------------------------------------------------------------------------
*/
Route::get(
    '/notifikasi/{teamRequest}',
    function (

        Request $request,

        TeamRequest $teamRequest

    ) {



        $user =

            $request->user();





        /*

        |--------------------------------------------------------------------------

        | LOAD DATA

        |--------------------------------------------------------------------------

        */



        $teamRequest->load([

            'sender',

            'project',

        ]);





        /*

        |--------------------------------------------------------------------------

        | PROJECT HARUS ADA

        |--------------------------------------------------------------------------

        */



        abort_unless(

            $teamRequest->project,

            404

        );





        /*

        |--------------------------------------------------------------------------

        | NOTIFIKASI HARUS MILIK USER LOGIN

        |--------------------------------------------------------------------------

        */



        abort_unless(



            (int) $teamRequest->recipient_id

                === (int) $user->id



            &&



            (int) $teamRequest->project->owner_id

                === (int) $user->id,



            403

        );





        /*

        |--------------------------------------------------------------------------

        | TANDAI SUDAH DIBACA

        |--------------------------------------------------------------------------

        */



        if (is_null($teamRequest->read_at)) {



            $teamRequest->read_at =

                now();



            $teamRequest->save();



        }





        /*

        |--------------------------------------------------------------------------

        | PELAMAR

        |--------------------------------------------------------------------------

        */



        $applicant =

            $teamRequest->sender;





        $project =

            $teamRequest->project;





        /*

        |--------------------------------------------------------------------------

        | RIWAYAT PROJECT PELAMAR

        |--------------------------------------------------------------------------

        */



        $applicantProjects =

            Project::with([

                'owner',

                'members',

            ])



            ->where(

                function ($query) use ($applicant) {



                    /*

                    | Project milik pelamar

                    */



                    $query->where(

                        'owner_id',

                        $applicant->id

                    );





                    /*

                    | Atau project yang dia ikuti

                    */



                    $query->orWhereHas(

                        'members',

                        function ($memberQuery) use ($applicant) {



                            $memberQuery->where(

                                'users.id',

                                $applicant->id

                            );



                        }

                    );



                }

            )



            ->latest()



            ->get();





        return view(

            'v_notifications.show',

            compact(

                'teamRequest',

                'applicant',

                'project',

                'applicantProjects'

            )

        );



    }

)

->middleware('auth')

->name('notifications.show');



Route::get('/anggota/{user}', function (User $user) {

    return view('v_explore.member', compact('user'));

})->name('member.show');



Route::post('/team-requests', function (Request $request) {

    $data = $request->validate(['recipient_id' => ['required', 'integer', 'exists:users,id']]);

    abort_if($request->user()->id === (int) $data['recipient_id'], 422);



    TeamRequest::firstOrCreate(

    [

        'sender_id' => $request->user()->id,

        'recipient_id' => $data['recipient_id'],

        'project_id' => null,

    ],

    ['status' => 'pending']

);



    return back()->with('success', 'Ajakan tim berhasil dikirim.');

})->middleware(['auth'])->name('team-requests.store');





Route::post('/team-requests/{teamRequest}/accept', function (Request $request, TeamRequest $teamRequest) {

    abort_unless($teamRequest->recipient_id === $request->user()->id, 403);

    abort_if($teamRequest->status !== 'pending', 422);



    $project = $teamRequest->project ?: Project::create([

        'owner_id' => $teamRequest->sender_id,

        'title' => 'Kolaborasi ' . $teamRequest->sender->name . ' & ' . $request->user()->name,

        'description' => 'Projek kolaborasi baru.',

        'status' => 'ongoing',

    ]);

    $project->members()->syncWithoutDetaching([

        $teamRequest->sender_id => ['role' => 'Pengajak'],

        $teamRequest->recipient_id => ['role' => 'Anggota'],

    ]);

    $teamRequest->update(['project_id' => $project->id, 'status' => 'accepted']);



    return back()->with('success', 'Ajakan diterima. Projek kolaborasi dibuat.');

})->middleware(['auth'])->name('team-requests.accept');



Route::post('/team-requests/{teamRequest}/reject', function (Request $request, TeamRequest $teamRequest) {

    abort_unless($teamRequest->recipient_id === $request->user()->id, 403);

    $teamRequest->update(['status' => 'rejected']);

    return back()->with('success', 'Ajakan ditolak.');

})->middleware(['auth'])->name('team-requests.reject');



Route::view(

    '/projek-saya/buat',

    'v_projects.create'

)

->middleware('auth')

->name('projects.create');



Route::post('/projek-saya', function (Request $request) {$data = $request->validate([

        'title' => [

            'required',

            'string',

            'max:255',

        ],



        'description' => [

            'required',

            'string',

            'max:5000',

        ],



        'deadline' => [

            'nullable',

            'date',

        ],





        /*

        |--------------------------------------------------------------------------

        | ROLE

        |--------------------------------------------------------------------------

        */



        'roles' => [

            'nullable',

            'array',

        ],



        'roles.*.name' => [

            'nullable',

            'string',

            'max:100',

        ],



        'roles.*.criteria' => [

            'nullable',

            'string',

            'max:500',

        ],





        /*

        |--------------------------------------------------------------------------

        | MILESTONE

        |--------------------------------------------------------------------------

        */
        'milestones' => [
            'nullable',
            'array',
        ],
        'milestones.*' => [
            'nullable',
            'string',
            'max:255',
        ],
        /*
        |--------------------------------------------------------------------------
        | DOCUMENTS
        |--------------------------------------------------------------------------
        */
        'documents' => [
            'nullable',
            'array',
        ],
        'documents.*' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,png,jpg,jpeg,webp',
            'max:10240',
        ],
    ]);
    /*
    |--------------------------------------------------------------------------
    | BERSIHKAN ROLE
    |--------------------------------------------------------------------------
    */
    $roles =
        array_values(
            array_filter(
                $data['roles'] ?? [],
                function ($role) {
                    return filled(
                        $role['name'] ?? null
                    )
                    ||
                    filled(
                        $role['criteria'] ?? null
                    );
                }
            )
        );
    /*
    |--------------------------------------------------------------------------
    | BERSIHKAN MILESTONE
    |--------------------------------------------------------------------------
    */
    $milestones =
        array_values(
            array_filter(
                $data['milestones'] ?? [],
                'filled'
            )
        );
    /*
    |--------------------------------------------------------------------------
    | UPLOAD DOKUMEN
    |--------------------------------------------------------------------------
    */
    $documents = [];
    if ($request->hasFile('documents')) {
        foreach (
            $request->file('documents')
            as $file
        ) {
            if (!$file) {
                continue;
            }
            $path =
                $file->store(
                    'project-documents',
                    'public'
                );
            $documents[] = [
                'name' =>
                    $file->getClientOriginalName(),
                'path' =>
                    $path,
                'type' =>
                    $file->getClientMimeType(),
                'size' =>
                    $file->getSize(),
            ];
        }
    }
    /*
    |--------------------------------------------------------------------------
    | BUAT PROJECT
    |--------------------------------------------------------------------------
    */
    $project = Project::create([
        'owner_id' =>
            $request->user()->id,
        'title' =>
            $data['title'],
        'description' =>
            $data['description'],
        'deadline' =>
            $data['deadline'] ?? null,
        'roles' =>
            $roles,
        'milestones' =>
            $milestones,
        'documents' =>
            $documents,
        'status' =>
            'ongoing',
    ]);
    /*
    |--------------------------------------------------------------------------
    | MASUKKAN OWNER SEBAGAI MEMBER
    |--------------------------------------------------------------------------
    */
    $project
        ->members()
        ->syncWithoutDetaching([
            $request->user()->id => [
                'role' => 'Pemilik',
            ],
        ]);
    return redirect()
        ->route('projects')
        ->with(
            'success',
            'Projek berhasil dibuat.'
        );
})
->middleware('auth')
->name('projects.store');
Route::delete('/projek-saya/{project}', function (Request $request, Project $project) {
    abort_unless($project->owner_id === $request->user()->id, 403);
    $project->delete();
    return back()->with('success', 'Projek berhasil dihapus.');
})->middleware('auth')->name('projects.destroy');
Route::post('/projek-saya/{project}/selesai', function (Request $request, Project $project) {
    abort_unless((int) $project->owner_id === (int) $request->user()->id, 403);
    if ($project->status !== 'ongoing') {
        throw \Illuminate\Validation\ValidationException::withMessages(['project' => 'Projek ini sudah selesai.']);
    }
    $project->update(['status' => 'completed']);
    return redirect()->route('projects', ['tab' => 'created'])->with('success', 'Projek berhasil ditandai selesai.');
})->middleware('auth')->name('projects.complete');
Route::get('/projek-saya', function (Request $request) {
    $userId = $request->user()->id;
    // Projek milik pengguna.
    $createdProjects = Project::with(['owner', 'members'])
        ->where('owner_id', $userId)
        ->latest()
        ->get();
    // Hanya projek yang pengguna sudah resmi menjadi anggotanya.
    $joinedProjects = Project::with(['owner', 'members'])
        ->where('owner_id', '!=', $userId)
        ->whereHas('members', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })
        ->latest()
        ->get();
    // Ditampilkan kepada pemilik untuk diterima atau ditolak.
    $incomingRequests = TeamRequest::with(['sender', 'project'])
        ->where('recipient_id', $userId)
        ->where('sender_id', '!=', $userId)
        ->where('status', 'pending')
        ->whereHas('project', function ($query) use ($userId) {
            $query->where('owner_id', $userId);
        })
        ->latest()
        ->get();
    // Ditampilkan kepada pengguna yang sedang menunggu persetujuan.
    $pendingApplications = TeamRequest::with('project')
        ->where('sender_id', $userId)
        ->where('status', 'pending')
        ->whereHas('project', function ($query) use ($userId) {
            $query->where('owner_id', '!=', $userId);
        })
        ->latest()
        ->get();
    return view('v_projects.index', compact(
        'createdProjects',
        'joinedProjects',
        'incomingRequests',
        'pendingApplications'
    ));
})->middleware('auth')->name('projects');
Route::get('/projek-saya/{project}/kelola', function (Request $request, Project $project) {
    $isOwner = (int) $project->owner_id === (int) $request->user()->id;
    $isMember = $project->members()->whereKey($request->user()->id)->exists();
    abort_unless($isOwner || $isMember, 403);
    $project->load(['owner', 'members']);
    return view('v_projects.workspace', compact('project', 'isOwner'));
})->middleware('auth')->name('projects.workspace');
Route::post('/projek-saya/{project}/kelola/capaian', function (Request $request, Project $project) {
    abort_unless((int) $project->owner_id === (int) $request->user()->id, 403);
    $data = $request->validate(['title' => ['required', 'string', 'max:255']]);
    $milestones = $project->milestones ?? [];
    $milestones[] = ['title' => $data['title'], 'status' => 'pending'];
    $project->update(['milestones' => array_values($milestones)]);
    return back()->with('success', 'Capaian berhasil ditambahkan.');
})->middleware('auth')->name('projects.workspace.milestones.store');
Route::post('/projek-saya/{project}/kelola/capaian/{index}/toggle', function (Request $request, Project $project, int $index) {
    abort_unless((int) $project->owner_id === (int) $request->user()->id, 403);
    $milestones = $project->milestones ?? [];
    abort_unless(array_key_exists($index, $milestones), 404);
    $milestone = $milestones[$index];
    $milestone = is_array($milestone) ? $milestone : ['title' => $milestone];
    $milestone['status'] = preg_match('/selesai|done|completed/i', (string) ($milestone['status'] ?? '')) ? 'pending' : 'completed';
    $milestones[$index] = $milestone;
    $project->update(['milestones' => array_values($milestones)]);
    return back()->with('success', 'Status capaian diperbarui.');
})->middleware('auth')->whereNumber('index')->name('projects.workspace.milestones.toggle');
Route::post('/projek-saya/{project}/kelola/dokumen', function (Request $request, Project $project) {
    abort_unless((int) $project->owner_id === (int) $request->user()->id, 403);
    $data = $request->validate([
        'document' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,png,jpg,jpeg,webp'],
    ]);
    $file = $data['document'];
    $documents = $project->documents ?? [];
    $documents[] = ['name' => $file->getClientOriginalName(), 'path' => $file->store('project-documents', 'public')];
    $project->update(['documents' => array_values($documents)]);
    return back()->with('success', 'Dokumen berhasil diunggah.');
})->middleware('auth')->name('projects.workspace.documents.store');
/*
|--------------------------------------------------------------------------
| CHAT - BUKA HALAMAN
|--------------------------------------------------------------------------
*/
Route::get('/projek-saya/{project}/chat', function (
    Request $request,
    Project $project
) {
    $user = $request->user();
    $canAccess =
        (int) $project->owner_id === (int) $user->id
        ||
        $project->members()
            ->whereKey($user->id)
            ->exists();
    abort_unless($canAccess, 403);
    $messages = $project
        ->chatMessages()
        ->with('user')
        ->oldest()
        ->get();
    $memberCount = $project
        ->members()
        ->count();
    return view(
        'v_projects.chat',
        compact(
            'project',
            'messages',
            'memberCount'
        )
    );
})
->middleware('auth')
->name('projects.chat');
/*
|--------------------------------------------------------------------------
| CHAT - AMBIL PESAN BARU (REALTIME)
|--------------------------------------------------------------------------
*/
Route::get(
    '/projek-saya/{project}/chat/messages',
    function (
        Request $request,
        Project $project
    ) {
        $user = $request->user();
        $canAccess =
            (int) $project->owner_id === (int) $user->id
            ||
            $project->members()
                ->whereKey($user->id)
                ->exists();
        abort_unless($canAccess, 403);
        $lastId =
            (int) $request->query(
                'last_id',
                0
            );
        $messages = $project
            ->chatMessages()
            ->with('user')
            ->where('id', '>', $lastId)
            ->oldest()
            ->get()
            ->map(function ($message) use ($user) {
                return [
                    'id' =>
                        $message->id,
                    'message' =>
                        $message->message,
                    'user_id' =>
                        $message->user_id,
                    'user_name' =>
                        $message->user?->name ?? 'Pengguna',
                    'is_me' =>
                        (int) $message->user_id
                        === (int) $user->id,
                    'time' =>
                        $message
                            ->created_at
                            ->timezone('Asia/Jakarta')
                            ->format('H:i'),
                ];
            });
        return response()->json([
            'messages' => $messages,
        ]);
    }
)
->middleware('auth')
->name('projects.chat.messages');
/*
|--------------------------------------------------------------------------
| CHAT - KIRIM PESAN
|--------------------------------------------------------------------------
*/
Route::post(
    '/projek-saya/{project}/chat',
    function (
        Request $request,
        Project $project
    ) {
        $user = $request->user();
        $canAccess =
            (int) $project->owner_id === (int) $user->id
            ||
            $project->members()
                ->whereKey($user->id)
                ->exists();
        abort_unless(
            $canAccess,
            403
        );
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */
        $data =
            $request->validate([
                'message' => [
                    'required',
                    'string',
                    'max:2000',
                ],
            ]);
        /*
        |--------------------------------------------------------------------------
        | SIMPAN PESAN
        |--------------------------------------------------------------------------
        */
        $message =
            new ChatMessage();
        $message->project_id =
            $project->id;
        $message->user_id =
            $user->id;
        $message->message =
            $data['message'];
        $message->save();
        /*
        |--------------------------------------------------------------------------
        | LOAD USER
        |--------------------------------------------------------------------------
        */
        $message->load('user');
        /*
        |--------------------------------------------------------------------------
        | BALIKKAN JSON
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'success' => true,
            'message' => [
                'id' =>
                    $message->id,
                'message' =>
                    $message->message,
                'user_id' =>
                    $message->user_id,
                'user_name' =>
                    $message->user?->name ?? 'Pengguna',
                'is_me' =>
                    true,
                'time' =>
                    $message
                        ->created_at
                        ->timezone('Asia/Jakarta')
                        ->format('H:i'),
            ],
        ]);
    }
)
->middleware('auth')
->name('projects.chat.store');
Route::view('/profile', 'v_profile.profile')->middleware('auth')->name('profile');
Route::get('/profile/edit', function (Request $request) {
    return view('v_profile.edit-profile', ['user' => $request->user()]);
})->middleware('auth')->name('profile.edit');
Route::get('/profile/edit-alias', function (Request $request) {
    return redirect()->route('profile.edit');
})->middleware('auth')->name('edit-profile');
Route::put('/profile', function (Request $request) {
    $user = $request->user();
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'study_program' => ['nullable', 'string', 'max:255'],
        'semester' => ['nullable', 'integer', 'between:1,14'],
        'university' => ['nullable', 'string', 'max:255'],
        'team_status' => ['required', 'in:active,inactive'],
        'skills' => ['nullable', 'array'],
        'skills.*' => ['required', 'string', 'max:100'],
        'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:10240', 'dimensions:max_width=6000,max_height=6000'],
        'remove_profile_photo' => ['nullable', 'boolean'],
    ]);
    if ($request->boolean('remove_profile_photo')) {
        $data['profile_photo'] = null;
    }
    if ($request->hasFile('profile_photo')) {
        $file = $request->file('profile_photo');
        $image = match ($file->getMimeType()) {
            'image/jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png' => imagecreatefrompng($file->getRealPath()),
            'image/webp' => imagecreatefromwebp($file->getRealPath()),
        };
        abort_unless($image, 422, 'Foto tidak dapat diproses.');
        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min(1, 512 / max($width, $height));
        $resized = imagescale($image, max(1, (int) round($width * $scale)), max(1, (int) round($height * $scale)));
        imagedestroy($image);
        abort_unless($resized, 422, 'Foto tidak dapat diproses.');
        $path = 'profile-photos/'.Str::uuid().'.webp';
        $output = fopen('php://temp', 'w+');
        $encoded = imagewebp($resized, $output, 80);
        imagedestroy($resized);
        abort_unless($encoded, 422, 'Foto tidak dapat diproses.');
        rewind($output);
        try {
            abort_unless(Storage::disk('public')->put($path, $output), 500, 'Foto gagal disimpan.');
        } finally {
            fclose($output);
        }
        $data['profile_photo'] = $path;
    }
    unset($data['remove_profile_photo']);
    unset($data['skills']);
    $oldPhoto = $user->profile_photo;
    try {
        $user->update($data);
    } catch (\Throwable $e) {
        if (isset($path)) Storage::disk('public')->delete($path);
        throw $e;
    }
    if ($oldPhoto && $oldPhoto !== $user->profile_photo) Storage::disk('public')->delete($oldPhoto);
    return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
})->middleware('auth')->name('profile.update');
