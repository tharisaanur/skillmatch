<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProjectWorkspaceRoutesTest extends TestCase
{
    public function test_registration_without_email_verification_opens_skill_selection(): void
    {
        $user = null;
        $email = 'uji-skill-'.uniqid().'@example.test';
        try {
            $response = $this->post('/register', [
                'name' => 'Uji Skill',
                'email' => $email,
                'password' => 'Password@123',
                'password_confirmation' => 'Password@123',
            ]);
            $user = \App\Models\User::where('email', $email)->first();
            $response->assertRedirect(route('complete-profile'));
            $this->assertAuthenticated();
            $this->get('/complete-profile')->assertOk()->assertSee('name="skills[]"', false);
            $this->post('/complete-profile', ['skills' => ['Python']])->assertRedirect(route('home'));
            $this->assertSame(['Python'], $user->fresh()->skills);
        } finally {
            if ($user) $user->delete();
        }
    }

    public function test_skill_editor_is_separate_and_profile_save_preserves_skills(): void
    {
        $user = \App\Models\User::factory()->create(['skills' => ['Python', 'Keahlian Khusus']]);
        try {
            $this->actingAs($user)->get('/profile/edit')->assertOk()
                ->assertSee('Kelola Keahlian Utama')->assertDontSee('name="skills[]"', false);
            $this->get('/profile/skills')->assertOk()
                ->assertSee('value="Python" checked', false)
                ->assertSee('value="Keahlian Khusus" checked', false);
            $this->put('/profile', [
                'name' => $user->name, 'email' => $user->email, 'team_status' => 'active',
            ])->assertRedirect(route('profile'));
            $this->assertSame(['Python', 'Keahlian Khusus'], $user->fresh()->skills);
            $this->post('/complete-profile', ['skills' => ['Figma'], 'from_profile' => '1'])
                ->assertRedirect(route('profile'));
            $this->assertSame(['Figma'], $user->fresh()->skills);
        } finally {
            $user->delete();
        }
    }

    public function test_edit_profile_shows_saved_choices_and_keeps_custom_skills(): void
    {
        $user = \App\Models\User::factory()->create(['skills' => ['Python', 'Keahlian Khusus']]);
        try {
            $this->actingAs($user)->get('/profile/skills')->assertOk()
                ->assertSee('value="Python" checked', false)
                ->assertSee('value="Keahlian Khusus" checked', false)
                ->assertSee('Manajemen Projek');
            $this->post('/complete-profile', [
                'skills' => ['Figma', 'Keahlian Khusus'], 'from_profile' => '1',
            ])->assertRedirect(route('profile'));
            $this->assertSame(['Figma', 'Keahlian Khusus'], $user->fresh()->skills);
            $this->post('/complete-profile', ['from_profile' => '1'])->assertRedirect(route('profile'));
            $this->assertSame([], $user->fresh()->skills);
            $this->get('/profile/skills')->assertOk()->assertSee('value="Python"', false);
        } finally {
            $user->delete();
        }
    }

    public function test_navbar_uses_logo_when_account_has_no_photo(): void
    {
        $this->be(new \App\Models\User(['name' => 'Uji Avatar']));
        $html = view('v_layouts.navbar')->render();

        $this->assertStringContainsString('src="'.asset('images/logo-avatar.png').'"', $html);
        $this->assertFileExists(public_path('images/logo-avatar.png'));
        $this->assertStringContainsString('class="profile-avatar-img"', $html);
    }

    public function test_profile_pages_use_icon_when_photo_is_empty(): void
    {
        $this->be(new \App\Models\User(['name' => 'Uji Avatar', 'email' => 'uji@example.test']));

        $this->get('/profile')->assertOk()->assertSee('images/logo-avatar.png');
        $this->get('/profile/edit')->assertOk()->assertSee('data-default-avatar="'.asset('images/logo-avatar.png').'"', false);
    }

    public function test_changing_profile_photo_persists_and_renders_uploaded_photo(): void
    {
        $user = \App\Models\User::factory()->create(['team_status' => 'active']);
        try {
            $this->actingAs($user)->put('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'team_status' => 'active',
                'profile_photo' => \Illuminate\Http\UploadedFile::fake()->image('avatar.png'),
            ])->assertRedirect(route('profile'));

            $photo = $user->fresh()->profile_photo;
            $this->assertNotNull($photo);
            $this->assertFileExists(storage_path('app/public/'.$photo));
            $this->assertFileExists(public_path('storage/'.$photo));
            $this->get('/profile')->assertOk()->assertSee('storage/'.$photo);
        } finally {
            if (isset($photo)) \Illuminate\Support\Facades\Storage::disk('public')->delete($photo);
            $user->delete();
        }
    }

    public function test_large_profile_photo_is_reduced_before_saving(): void
    {
        $user = \App\Models\User::factory()->create(['team_status' => 'active']);
        $source = tempnam(sys_get_temp_dir(), 'avatar');
        $image = imagecreatetruecolor(1200, 1200);
        for ($y = 0; $y < 1200; $y++) {
            for ($x = 0; $x < 1200; $x++) {
                imagesetpixel($image, $x, $y, random_int(0, 0xffffff));
            }
        }
        imagepng($image, $source);
        imagedestroy($image);
        $this->assertGreaterThan(2 * 1024 * 1024, filesize($source));
        try {
            $this->actingAs($user)->put('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'team_status' => 'active',
                'profile_photo' => new \Illuminate\Http\UploadedFile($source, 'large.png', 'image/png', null, true),
            ])->assertRedirect(route('profile'))->assertSessionHasNoErrors();
            $photo = $user->fresh()->profile_photo;
            $this->assertNotNull($photo);
            $this->assertLessThanOrEqual(2 * 1024 * 1024, filesize(storage_path('app/public/'.$photo)));
            $this->assertLessThanOrEqual(512, getimagesize(storage_path('app/public/'.$photo))[0]);
            $this->get('/profile')->assertOk()->assertSee('storage/'.$photo);
        } finally {
            if (isset($photo)) \Illuminate\Support\Facades\Storage::disk('public')->delete($photo);
            unlink($source);
            $user->delete();
        }
    }

    public function test_rejected_profile_photo_shows_upload_error(): void
    {
        $user = \App\Models\User::factory()->create(['team_status' => 'active']);
        try {
            $this->actingAs($user)->from('/profile/edit')->put('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'team_status' => 'active',
                'profile_photo' => \Illuminate\Http\UploadedFile::fake()->image('too-big.png')->size(11000),
            ])->assertRedirect('/profile/edit')->assertSessionHasErrors('profile_photo');

            $this->get('/profile/edit')->assertOk()->assertSee('Foto maksimal 10 MB sebelum diperkecil.');
        } finally {
            $user->delete();
        }
    }

    public function test_explore_cards_show_real_details_and_progress(): void
    {
        $owner = \App\Models\User::factory()->create();
        $visitor = \App\Models\User::factory()->create();
        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Projek Uji Progres',
            'description' => 'Deskripsi uji',
            'status' => 'ongoing',
            'deadline' => '2030-12-31',
            'milestones' => [
                ['title' => 'Tahap satu', 'status' => 'completed'],
                ['title' => 'Tahap dua', 'status' => 'pending'],
            ],
        ]);
        try {
            $response = $this->actingAs($visitor)->get('/jelajahi-projek');
            $response->assertOk()->assertSee('Projek Uji Progres')->assertSee('50%')
                ->assertSee('31 Desember 2030')->assertSee('Lihat Detail')
                ->assertSee('Gabung')->assertSee('0 anggota');
            $this->get('/jelajahi-projek/'.$project->id)->assertOk()->assertSee('Projek Uji Progres')
                ->assertSee('Tahap satu')->assertSee('Gabung');
        } finally {
            $project->delete();
            $visitor->delete();
            $owner->delete();
        }
    }

    public function test_only_owner_can_finish_project_and_it_remains_in_their_list(): void
    {
        $owner = \App\Models\User::factory()->create();
        $member = \App\Models\User::factory()->create();
        $project = Project::create(['owner_id' => $owner->id, 'title' => 'Projek Selesai Uji', 'description' => 'Uji', 'status' => 'ongoing']);
        try {
            $project->members()->attach($member->id, ['role' => 'Anggota']);
            $url = '/projek-saya/'.$project->id.'/selesai';
            $this->actingAs($member)->post($url)->assertForbidden();
            $this->assertSame('ongoing', $project->fresh()->status);
            $this->actingAs($owner)->get('/projek-saya')->assertOk()->assertSee('class="complete-project-btn">Projek Selesai</button>', false);
            $this->post($url)->assertRedirect(route('projects', ['tab' => 'created']));
            $this->assertSame('completed', $project->fresh()->status);
            $this->get('/projek-saya')->assertOk()->assertSee('Projek Selesai Uji')->assertSee('Projek Selesai');
            $this->get('/jelajahi-projek')->assertOk()->assertDontSee('Projek Selesai Uji');
            $this->actingAs($member)->post('/projek-saya/'.$project->id.'/gabung')->assertSessionHasErrors('join');
            $this->actingAs($owner)->post($url)->assertSessionHasErrors('project');
        } finally {
            $project->members()->detach();
            $project->delete();
            $member->delete();
            $owner->delete();
        }
    }

    public function test_owner_workspace_mutations_are_post_routes(): void
    {
        foreach (['projects.workspace.milestones.store', 'projects.workspace.milestones.toggle', 'projects.workspace.documents.store'] as $name) {
            $route = Route::getRoutes()->getByName($name);

            $this->assertNotNull($route);
            $this->assertSame(['POST'], $route->methods());
        }
    }

    public function test_document_upload_requires_selection_before_submit(): void
    {
        $project = new Project(['title' => 'Uji', 'status' => 'ongoing']);
        $project->id = 1;
        $html = view('v_projects.workspace', [
            'project' => $project,
            'isOwner' => true,
            'errors' => new \Illuminate\Support\ViewErrorBag,
        ])->render();

        $this->assertStringContainsString('<div class="workspace-hero-top">', $html);
        $this->assertStringContainsString('class="workspace-label">Kelola Projek</span>', $html);
        $this->assertStringContainsString('id="project-document"', $html);
        $this->assertStringContainsString('type="file" class="sr-only" required', $html);
        $this->assertStringContainsString('<label for="project-document" class="workspace-file-picker">Choose File</label>', $html);
        $this->assertStringContainsString('type="submit">Upload File', $html);

        $css = file_get_contents(resource_path('css/projects.css'));
        $this->assertStringContainsString('.workspace-upload-form { grid-template-columns: 1fr auto; align-items: center; }', $css);
        $this->assertStringContainsString('.workspace-upload-form button { display: none; }', $css);
        $this->assertStringContainsString('.workspace-upload-form:has(input[type="file"]:valid) button { display: block; justify-self: end; }', $css);
    }
}
