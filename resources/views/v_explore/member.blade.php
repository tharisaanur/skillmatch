@extends('v_layouts.app')
@section('title', 'Profil Anggota - SkillMatch')
@push('styles')
    @vite('resources/css/explore.css')
@endpush
@section('content')
@include('v_layouts.navbar')
<main class="explore-page member-profile-page">
    <a class="back-link" href="{{ route('explore') }}">← Kembali ke Temukan Tim Projek</a>
    <section class="member-profile-card">
        <div class="member-profile-head">
            <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/logo.png') }}" alt="Foto {{ $user->name }}">
            <div>
                <span class="availability"><i></i> Aktif mencari tim projek</span>
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->study_program ?: 'Mahasiswa' }} · {{ $user->university ?: 'Universitas belum diisi' }}</p>
            </div>
        </div>
        <div class="profile-detail-grid">
            <div><small>EMAIL</small><strong>{{ $user->email }}</strong></div>
            <div><small>SEMESTER</small><strong>{{ $user->semester ?: '-' }}</strong></div>
            <div class="profile-skills"><small>KEAHLIAN UTAMA</small><div class="skill-tags">@forelse($user->skills ?? [] as $skill)<span>{{ $skill }}</span>@empty<span>Belum ada keahlian</span>@endforelse</div></div>
        </div>
    </section>
</main>
@endsection
