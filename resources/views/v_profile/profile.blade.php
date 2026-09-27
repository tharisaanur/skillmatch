@extends('v_layouts.app')

@section('title', 'Profil - SkillMatch')

@push('styles')
    @vite('resources/css/profile.css')
@endpush

@section('content')

@php
    $user = Auth::user();
@endphp

<div class="profile-page">

    {{-- NAVBAR --}}
    @include('v_layouts.navbar')


    {{-- CONTENT --}}
    <main class="profile-main">

        {{-- HEADER PROFIL --}}
        <section class="profile-header">

            <div class="profile-header-left">

                <img
                    src="{{ $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : asset('images/logo-avatar.png') }}"
                    alt="Foto Profil"
                    class="profile-header-avatar"
                >

                <div class="profile-header-text">

                    <p class="profile-subtitle">
                        Profil Akun Saya
                    </p>

                    <h1>
                        {{ $user->name }}
                    </h1>

                    <p class="profile-email">
                        {{ $user->email }}
                    </p>

                </div>

            </div>


            <div class="profile-header-right">

                <a
                    href="{{ route('profile.edit') }}"
                    class="edit-profile-btn"
                >
                    <span class="edit-icon">✎</span>
                    Edit Profil
                </a>

            </div>

        </section>


        {{-- GRID CARD --}}
        <section class="profile-grid">

            {{-- CARD KIRI --}}
            <div class="profile-card">

                <h2>
                    Informasi Akun & Skill
                </h2>

                <p class="card-desc">
                    Data akademik dan keahlian yang terdaftar
                </p>


                {{-- UNIVERSITAS --}}
                <div class="info-box">

                    <span class="info-label">
                        UNIVERSITAS
                    </span>

                    <strong>
                        {{ $user->university ?: '-' }}
                    </strong>

                </div>


                {{-- PROGRAM STUDI & SEMESTER --}}
                <div class="info-box">

                    <span class="info-label">
                        PROGRAM STUDI & SEMESTER
                    </span>

                    <strong>

                        {{ $user->study_program ?: '-' }}

                        @if($user->semester)
                            (semester {{ $user->semester }})
                        @endif

                    </strong>

                </div>


                {{-- KEAHLIAN --}}
                <div class="info-box">

                    <span class="info-label">
                        KEAHLIAN UTAMA
                    </span>

                    <div class="skill-tags">

                        @forelse($user->skills ?? [] as $skill)

                            <span>
                                {{ $skill }}
                            </span>

                        @empty

                            <span>
                                Belum ada keahlian
                            </span>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- CARD KANAN --}}
            <div class="profile-card">

                <h2>
                    Status & Riwayat Projek
                </h2>

                <p class="card-desc">
                    Ketersedian pencarian tim dan proyek aktif
                </p>


                {{-- STATUS --}}
                <div class="status-box">

                    <span class="info-label">
                        STATUS PENCARIAN TIM
                    </span>

                    @if($user->team_status === 'inactive')

                        <div class="status-indicator status-inactive">
                            <span class="status-dot" aria-hidden="true"></span>
                            <span>Tidak Mencari Projek</span>
                        </div>

                    @else

                        <div class="status-indicator status-active">
                            <span class="status-dot" aria-hidden="true"></span>
                            <span>Aktif Mencari Projek</span>
                        </div>

                    @endif

                </div>


                {{-- PROJECT --}}
                <div class="project-section">

                    <span class="info-label project-label">PROJEK SAYA</span>

                    @forelse($user->projects()->latest()->get() as $project)
                        <div class="project-item">
                            <div class="project-item-head">
                                <h3>{{ $project->title }}</h3>
                                <span class="project-badge">{{ $project->owner_id === $user->id ? 'OWNER' : 'ANGGOTA TIM' }}</span>
                            </div>
                            <p>{{ $project->description }}</p>
                        </div>
                    @empty
                        <div class="project-item"><p>Belum ada projek.</p></div>
                    @endforelse

                </div>

            </div>

        </section>

    </main>

</div>

@endsection