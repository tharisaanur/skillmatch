@extends('v_layouts.app')

@section('title', 'Projek Saya - SkillMatch')

@push('styles')
    @vite(['resources/css/hero.css','resources/css/projects.css'])
@endpush

@section('content')

{{-- NAVBAR --}}
@include('v_layouts.navbar')


<main class="projects-page">

    <section class="projects-container">


        {{-- HEADER --}}
        <div class="projects-header">

            <h1>Projek Saya</h1>

            <a
                href="{{ route('projects.create') }}"
                class="create-project-btn"
            >
                <span class="create-icon" aria-hidden="true">+</span>
                Buat Projek Baru
            </a>

        </div>

        {{-- NOTIFIKASI --}}
        @if(session('success'))

            <div class="project-success">
                {{ session('success') }}
            </div>

        @endif


        @if($errors->any())
    <div class="project-success" role="alert">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

{{-- PERMINTAAN MASUK UNTUK PEMILIK --}}
@if($incomingRequests->isNotEmpty())
    <section class="join-requests" aria-labelledby="incomingHeading">
        <h2 id="incomingHeading">Permintaan Bergabung</h2>

        @foreach($incomingRequests as $application)
            <article class="join-request-card">
                <div>
                    <h3>{{ $application->sender?->name ?? 'Pengguna' }}</h3>

                    <p>
                        Ingin bergabung ke
                        <strong>{{ $application->project->title }}</strong>.
                    </p>
                </div>

                <div class="join-request-actions">
                    <form
                        method="POST"
                        action="{{ route('projects.requests.decide', [
                            'project' => $application->project_id,
                            'teamRequest' => $application->id,
                            'decision' => 'tolak'
                        ]) }}"
                    >
                        @csrf
                        <button type="submit" class="join-reject">
                            Tolak
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('projects.requests.decide', [
                            'project' => $application->project_id,
                            'teamRequest' => $application->id,
                            'decision' => 'terima'
                        ]) }}"
                    >
                        @csrf
                        <button type="submit" class="join-accept">
                            Terima
                        </button>
                    </form>
                </div>
            </article>
        @endforeach
    </section>
@endif

{{-- STATUS PERMINTAAN YANG DIKIRIM PENGGUNA --}}
@if($pendingApplications->isNotEmpty())
    <section class="join-requests" aria-labelledby="pendingHeading">
        <h2 id="pendingHeading">Permintaan Saya</h2>

        @foreach($pendingApplications as $application)
            <article class="join-request-card">
                <div>
                    <h3>{{ $application->project->title }}</h3>
                    <p>Menunggu persetujuan pemilik projek.</p>
                </div>

                <span class="join-pending">Menunggu Persetujuan</span>
            </article>
        @endforeach
    </section>
@endif

        {{-- TAB --}}
        <div class="projects-tabs">

            <button
                type="button"
                class="project-tab active"
                data-project-tab="created"
            >
                Projek Dibuat
            </button>

            <button
                type="button"
                class="project-tab"
                data-project-tab="joined"
            >
                Projek Diikuti
            </button>

        </div>



        {{-- ===========================================
            PROJEK DIBUAT
        ============================================ --}}
        <div
            class="project-panel"
            data-project-panel="created"
        >

            @forelse($createdProjects as $project)

                <article class="project-card">


                    {{-- BAGIAN ATAS --}}
                    <div class="project-card-top">

                        <span class="owner-badge">
                            Pemilik Projek
                        </span>


                        {{-- ROLE --}}
                        <div class="project-skills">

                            @if(!empty($project->roles))

                                @foreach(
                                    collect($project->roles)
                                        ->pluck('name')
                                        ->filter()
                                        ->take(3)
                                    as $role
                                )

                                    <span>
                                        {{ $role }}
                                    </span>

                                @endforeach

                            @endif

                        </div>

                    </div>



                    {{-- JUDUL --}}
                    <h2 class="project-title">
                        {{ $project->title }}
                    </h2>



                    {{-- INFO --}}
                    <div class="project-meta">

                        <span>
                            Anggota Tim:
                            <strong>
                                {{ $project->members->count() }}
                            </strong>
                        </span>


                        <span class="meta-space"></span>


                        <span>
                            Tenggat:

                            <strong>

                                @if($project->deadline)

                                    {{ \Carbon\Carbon::parse($project->deadline)
                                        ->translatedFormat('d F Y') }}

                                @else

                                    Belum ditentukan

                                @endif

                            </strong>

                        </span>

                    </div>



                    {{-- GARIS --}}
                    <div class="project-divider"></div>



                    {{-- FOOTER --}}
                    <div class="project-footer">

                        <div class="project-status">

                            <span class="status-dot"></span>

                            <span>
                                @if($project->status === 'ongoing')
                                    Projek Sedang Berjalan
                                @else
                                    {{ $project->status === 'completed' ? 'Projek Selesai' : ucfirst($project->status ?? 'Projek') }}
                                @endif
                            </span>

                        </div>


                        <div class="project-owner-actions">
                            @if($project->status === 'ongoing')
                                <form method="POST" action="{{ route('projects.complete', $project) }}" onsubmit="return confirm('Tandai projek ini selesai? Projek akan hilang dari Jelajahi Projek dan tidak menerima anggota baru.')">
                                    @csrf
                                    <button type="submit" class="complete-project-btn">Projek Selesai</button>
                                </form>
                            @endif
                            <a href="{{ route('projects.workspace', $project) }}" class="manage-project-btn">Kelola Projek</a>
                        </div>

                    </div>

                </article>


            @empty

                {{-- BELUM ADA PROJEK --}}
                <div class="empty-project">

                    <h3>
                        Belum ada projek yang dibuat
                    </h3>

                    <p>
                        Mulai buat projek pertamamu dan cari anggota tim
                        yang sesuai dengan kebutuhanmu.
                    </p>

                    <a
                        href="{{ route('projects.create') }}"
                        class="empty-create-btn"
                    >
                        + Buat Projek Baru
                    </a>

                </div>

            @endforelse

        </div>



        {{-- ===========================================
            PROJEK DIIKUTI
        ============================================ --}}
        <div
            class="project-panel"
            data-project-panel="joined"
            hidden
        >

            @forelse($joinedProjects as $project)

                <article class="project-card">


                    <div class="project-card-top">

                        <span class="owner-badge">
                            Anggota Tim
                        </span>


                        <div class="project-skills">

                            @if(!empty($project->roles))

                                @foreach(
                                    collect($project->roles)
                                        ->pluck('name')
                                        ->filter()
                                        ->take(3)
                                    as $role
                                )

                                    <span>
                                        {{ $role }}
                                    </span>

                                @endforeach

                            @endif

                        </div>

                    </div>



                    <h2 class="project-title">
                        {{ $project->title }}
                    </h2>



                    <div class="project-meta">

                        <span>
                            Pemilik Projek:

                            <strong>
                                {{ $project->owner?->name ?? '-' }}
                            </strong>
                        </span>


                        <span class="meta-space"></span>


                        <span>
                            Tenggat:

                            <strong>

                                @if($project->deadline)

                                    {{ \Carbon\Carbon::parse($project->deadline)
                                        ->translatedFormat('d F Y') }}

                                @else

                                    Belum ditentukan

                                @endif

                            </strong>

                        </span>

                    </div>



                    <div class="project-divider"></div>



                    <div class="project-footer">

                        <div class="project-status">

                            <span class="status-dot"></span>

                            <span>
                                @if($project->status === 'ongoing')
                                    Projek Sedang Berjalan
                                @else
                                    {{ $project->status === 'completed' ? 'Projek Selesai' : ucfirst($project->status ?? 'Projek') }}
                                @endif
                            </span>

                        </div>


                        <a
                            href="{{ route('projects.chat', $project) }}"
                            class="manage-project-btn"
                        >
                            Buka Projek
                        </a>

                    </div>

                </article>


            @empty

                <div class="empty-project">

                    <h3>
                        Belum ada projek yang diikuti
                    </h3>

                    <p>
                        Projek yang kamu ikuti akan tampil di sini.
                    </p>

                    <a
                        href="{{ route('explore') }}"
                        class="empty-create-btn"
                    >
                        Jelajahi Projek
                    </a>

                </div>

            @endforelse

        </div>

    </section>

</main>

@endsection



@push('scripts')
<script>
    (() => {
        const tabs = document.querySelectorAll('[data-project-tab]');
        const panels = document.querySelectorAll('[data-project-panel]');

        function activateTab(target) {
            tabs.forEach((tab) => {
                const active = tab.dataset.projectTab === target;

                tab.classList.toggle('active', active);
            });

            panels.forEach((panel) => {
                panel.hidden = panel.dataset.projectPanel !== target;
            });
        }

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.projectTab;

                activateTab(target);

                const url = new URL(window.location.href);
                url.searchParams.set('tab', target);
                window.history.replaceState({}, '', url);
            });
        });

        const initialTab = new URLSearchParams(
            window.location.search
        ).get('tab');

        activateTab(initialTab === 'joined' ? 'joined' : 'created');
    })();
</script>
@endpush

@push('styles')
<style>
    .join-requests {
        margin-bottom: 28px;
    }

    .join-requests h2 {
        margin: 0 0 14px;
        color: #fff;
        font-size: 22px;
    }

    .join-request-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 12px;
        padding: 18px 20px;
        border: 1px solid rgba(176, 237, 249, .65);
        border-radius: 18px;
        background: rgba(176, 237, 249, .09);
    }

    .join-request-card h3 {
        margin: 0 0 6px;
        color: #fff;
        font-size: 17px;
        overflow-wrap: anywhere;
    }

    .join-request-card p {
        margin: 0;
        color: #d3e8ef;
        font-size: 14px;
        line-height: 1.5;
    }

    .join-request-actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }

    .join-request-actions form {
        margin: 0;
    }

    .join-request-actions button {
        min-width: 90px;
        min-height: 38px;
        padding: 8px 14px;
        border: 1px solid #b0edf9;
        border-radius: 9px;
        font: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .join-accept {
        background: #b0edf9;
        color: #073c53;
    }

    .join-reject {
        background: #754451;
        color: #fff;
    }

    .join-pending {
        padding: 8px 12px;
        border: 1px solid #e6cf86;
        border-radius: 9px;
        color: #f6e4ac;
        font-size: 13px;
        text-align: center;
    }

    @media (max-width: 650px) {
        .join-request-card {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush