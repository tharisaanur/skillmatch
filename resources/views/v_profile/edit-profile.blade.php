@extends('v_layouts.app')

@section('title', 'Edit Profil - SkillMatch')

@push('styles')
    @vite('resources/css/edit-profile.css')
@endpush

@push('scripts')
    @vite('resources/js/edit-profile.js')
@endpush


@section('content')

@php
    $user = Auth::user();

    $teamStatus = old(
        'team_status',
        $user->team_status ?? 'active'
    );


@endphp


<div class="edit-profile-page">

    {{-- ================================
        NAVBAR
    ================================= --}}

    @include('v_layouts.navbar')



    {{-- ================================
        MAIN
    ================================= --}}

    <main class="edit-main" data-profile-form>

        <section class="edit-card">


            {{-- HEADER CARD --}}

            <div class="edit-card-header">

                <h1>
                    Edit Profile Saya
                </h1>

                <a
                    href="{{ route('profile') }}"
                    class="close-edit"
                    aria-label="Tutup"
                >
                    ×
                </a>

            </div>



            {{-- ================================
                FORM
            ================================= --}}

            <form
                action="{{ route('profile.update') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')



                {{-- ================================
                    FOTO PROFIL
                ================================= --}}

                @error('profile_photo')
                    <p role="alert" class="photo-error">Foto maksimal 10 MB sebelum diperkecil. Gunakan JPG, PNG, atau WebP. {{ $message }}</p>
                @enderror
                <div class="photo-section">
                    <div class="photo-preview-wrap">
                        <img
                            src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/logo-avatar.png') }}"
                            data-default-avatar="{{ asset('images/logo-avatar.png') }}"
                            alt="Foto Profil"
                            class="edit-avatar"
                            id="profilePreview"
                        >
                        <span class="photo-name">{{ $user->name }}</span>
                    </div>


                    <div class="photo-buttons">


                        {{-- GANTI FOTO --}}

                        <label
                            for="profile_photo"
                            class="change-photo-btn"
                        >
                            Ganti Foto
                        </label>


                        <input
                            type="file"
                            id="profile_photo"
                            name="profile_photo"
                            accept="image/jpeg,image/png,image/webp"
                            hidden
                        >



                        {{-- HAPUS FOTO --}}

                        <button
                            type="button"
                            class="delete-photo-btn"
                            id="deletePhoto"
                        >
                            Hapus
                        </button>



                        {{-- MEMBERITAHU BACKEND FOTO HARUS DIHAPUS --}}

                        <input
                            type="hidden"
                            id="remove_profile_photo"
                            name="remove_profile_photo"
                            value="0"
                        >

                    </div>

                </div>



                {{-- ================================
                    FORM GRID
                ================================= --}}

                <div class="edit-form-grid">


                    {{-- NAMA --}}

                    <div class="edit-form-group name-group">

                        <label for="name">
                            NAMA LENGKAP
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                        >

                    </div>



                    {{-- PROGRAM STUDI --}}

                    <div class="edit-form-group study-group">

                        <label for="study_program">
                            PROGRAM STUDI
                        </label>

                        <input
                            type="text"
                            id="study_program"
                            name="study_program"
                            value="{{ old('study_program', $user->study_program) }}"
                            placeholder="Masukkan program studi"
                        >

                    </div>



                    {{-- EMAIL --}}

                    <div class="edit-form-group email-group">

                        <label for="email">
                            EMAIL
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                        >

                    </div>



                    {{-- SEMESTER --}}

                    <div class="edit-form-group semester-group">

                        <label for="semester">
                            SEMESTER
                        </label>

                        <input
                            type="number"
                            id="semester"
                            name="semester"
                            min="1"
                            max="14"
                            value="{{ old('semester', $user->semester) }}"
                            placeholder="Contoh: 4"
                        >

                    </div>



                    {{-- UNIVERSITAS --}}

                    <div class="edit-form-group university-group">

                        <label for="university">
                            UNIVERSITAS
                        </label>

                        <input
                            type="text"
                            id="university"
                            name="university"
                            value="{{ old('university', $user->university) }}"
                            placeholder="Masukkan universitas"
                        >

                    </div>



                    {{-- ================================
                        STATUS PENCARIAN TIM
                    ================================= --}}

                    <div class="edit-form-group status-group">

                        <label for="team_status">
                            STATUS PENCARIAN TIM
                        </label>


                        <div
                            class="status-select-wrapper
                            {{ $teamStatus === 'inactive'
                                ? 'status-inactive'
                                : 'status-active' }}"
                            id="statusWrapper"
                        >

                            <span class="status-dot" aria-hidden="true"></span>


                            <select
                                id="team_status"
                                name="team_status"
                            >

                                <option
                                    value="active"
                                    style="color: #8BC34A;"
                                    {{ $teamStatus === 'active'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Aktif Mencari Projek
                                </option>


                                <option
                                    value="inactive"
                                    style="color: #ff3b3b;"
                                    {{ $teamStatus === 'inactive'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Tidak Mencari Projek
                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- ================================
                        KEAHLIAN UTAMA
                    ================================= --}}

                    <div class="edit-form-group skills-group">

                        <label>
                            KEAHLIAN UTAMA
                        </label>


                        <a href="{{ route('profile.skills.edit') }}" class="skill-link">Kelola Keahlian Utama</a>

                        <p class="skill-summary">{{ implode(', ', $user->skills ?? []) ?: 'Belum ada keahlian' }}</p>

                    </div>

                </div>



                {{-- ================================
                    BUTTON
                ================================= --}}

                <div class="edit-actions">

                    <a
                        href="{{ route('profile') }}"
                        class="cancel-btn"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="save-btn"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </section>

    </main>

    <div class="unsaved-dialog" id="unsavedDialog" hidden role="dialog" aria-modal="true" aria-labelledby="unsavedTitle">
        <div class="unsaved-backdrop" data-close-unsaved></div>
        <section class="unsaved-card">
            <h2 id="unsavedTitle">Profil belum disimpan</h2>
            <p>Perubahanmu belum disimpan. Simpan sebelum meninggalkan halaman?</p>
            <div class="unsaved-actions">
                <button type="button" class="unsaved-cancel" data-close-unsaved>Tetap di halaman</button>
                <button type="button" class="unsaved-save" id="saveBeforeLeave">Simpan</button>
            </div>
        </section>
    </div>

</div>

@endsection