@extends('v_layouts.app')

@section('title', 'Detail Permintaan - SkillMatch')


@push('styles')
    @vite('resources/css/hero.css')
    @vite('resources/css/notifications.css')
@endpush


@section('content')

@include('v_layouts.navbar')


@php

    $skills =
        is_array($applicant->skills)
            ? $applicant->skills
            : [];

    $photo =
        $applicant->profile_photo
            ? asset(
                'storage/' .
                $applicant->profile_photo
            )
            : asset(
                'images/profile-user.png'
            );

@endphp


<main class="notification-page">

    <section class="application-detail">


        {{-- SUCCESS --}}
        @if(session('success'))

            <div class="notification-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- HEADER PELAMAR --}}
        <section class="applicant-header">


            <div class="applicant-main">

                <img
                    src="{{ $photo }}"
                    alt="{{ $applicant->name }}"
                    class="applicant-avatar"
                >


                <div class="applicant-identity">

                    <span class="application-context">

                        Ingin bergabung ke:

                        <strong>
                            {{ $project->title }}
                        </strong>

                    </span>


                    <h1>
                        {{ $applicant->name }}
                    </h1>


                    <p>
                        {{ $applicant->email }}
                    </p>

                </div>

            </div>


                    {{-- BUTTON --}}
            <div class="application-actions">

                @if($teamRequest->status === 'pending')

                    {{-- TOLAK --}}
                    <form
                        action="{{ route(
                            'projects.requests.decide',
                            [
                                'project' => $project,
                                'teamRequest' => $teamRequest,
                                'decision' => 'tolak'
                            ]
                        ) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menolak permintaan ini?');"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="reject-application"
                        >
                            Tolak
                        </button>
                    </form>


                    {{-- TERIMA --}}
                    <form
                        action="{{ route(
                            'projects.requests.decide',
                            [
                                'project' => $project,
                                'teamRequest' => $teamRequest,
                                'decision' => 'terima'
                            ]
                        ) }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="accept-application"
                        >
                            Terima Join Tim
                        </button>
                    </form>


                @elseif($teamRequest->status === 'accepted')

                    <span class="application-result accepted">
                        ✓ Sudah Diterima
                    </span>


                @elseif($teamRequest->status === 'rejected')

                    <span class="application-result rejected">
                        Permintaan Ditolak
                    </span>

                @endif

            </div>
        </section>



        {{-- GRID DETAIL --}}
        <section class="applicant-grid">


            {{-- KIRI --}}
            <article class="applicant-panel">

                <h2>
                    Informasi Akun & Skill
                </h2>

                <p class="panel-description">
                    Data akademik dan keahlian yang terdaftar
                </p>


                <div class="profile-info-box">

                    <span>
                        UNIVERSITAS
                    </span>

                    <strong>
                        {{ $applicant->university
                            ?? 'Belum diisi'
                        }}
                    </strong>

                </div>


                <div class="profile-info-box">

                    <span>
                        PROGRAM STUDI & SEMESTER
                    </span>

                    <strong>

                        {{ $applicant->study_program
                            ?? 'Belum diisi'
                        }}

                        @if($applicant->semester)

                            (semester
                            {{ $applicant->semester }})

                        @endif

                    </strong>

                </div>


                <div class="profile-info-box">

                    <span>
                        KEAHLIAN UTAMA
                    </span>


                    <div class="skill-list">

                        @forelse(
                            $skills
                            as $skill
                        )

                            <span class="skill-chip">
                                {{ $skill }}
                            </span>

                        @empty

                            <span class="empty-skill">
                                Belum ada skill
                            </span>

                        @endforelse

                    </div>

                </div>

            </article>



            {{-- KANAN --}}
            <article class="applicant-panel">

                <h2>
                    Status & Riwayat Projek
                </h2>

                <p class="panel-description">
                    Ketersediaan pencarian tim dan projek aktif
                </p>


                <div class="profile-info-box">

                    <span>
                        STATUS PENCARIAN TIM
                    </span>


                    @if(
                        $applicant->team_status
                        === 'active'
                    )

                        <strong class="team-active">

                            <i></i>

                            Aktif Mencari Projek

                        </strong>

                    @else

                        <strong>
                            Tidak Sedang Mencari Projek
                        </strong>

                    @endif

                </div>


                <div class="project-history">

                    <span class="history-label">
                        PROJEK YANG DIBUAT/DIIKUTI
                    </span>


                    @forelse(
                        $applicantProjects
                        as $applicantProject
                    )

                        <div class="history-card">

                            <div>

                                <strong>
                                    {{ $applicantProject->title }}
                                </strong>

                                <p>
                                    {{ \Illuminate\Support\Str::limit(
                                        $applicantProject->description,
                                        75
                                    ) }}
                                </p>

                            </div>


                            @if(
                                (int)
                                $applicantProject->owner_id
                                ===
                                (int)
                                $applicant->id
                            )

                                <span class="history-role">
                                    OWNER
                                </span>

                            @else

                                <span class="history-role">
                                    ANGGOTA
                                </span>

                            @endif

                        </div>

                    @empty

                        <div class="history-empty">
                            Belum memiliki riwayat projek.
                        </div>

                    @endforelse

                </div>

            </article>

        </section>

    </section>

</main>

@endsection