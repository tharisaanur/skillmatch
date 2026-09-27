@extends('v_layouts.app')
@section('title', $project->title . ' - SkillMatch')
@push('styles')
    @vite('resources/css/explore.css')
@endpush
@section('content')
@include('v_layouts.navbar')
<main class="explore-page project-detail-page">
    <a class="back-link" href="{{ route('explore') }}">← Jelajahi Projek</a>
    <article class="member-profile-card">
        <span class="availability"><i></i> {{ $project->status === 'ongoing' ? 'Projek aktif' : 'Projek selesai' }}</span>
        <h1>{{ $project->title }}</h1>
        <p>Oleh {{ $project->owner?->name ?: 'Pengguna' }}</p>
        <p>{{ $project->description }}</p>
        <div class="profile-detail-grid">
            <div><small>TENGGAT</small><strong>{{ $project->deadline?->locale('id')->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</strong></div>
            <div><small>ANGGOTA</small><strong>{{ $project->members->count() }} anggota</strong></div>
            <div><small>PERAN DIBUTUHKAN</small><div class="skill-tags">@forelse($project->roles ?? [] as $role)<span>{{ $role['name'] ?? 'Peran' }}</span>@empty<span>Belum ditentukan</span>@endforelse</div></div>
            <div><small>CAPAIAN</small>@forelse($project->milestones ?? [] as $milestone)<p>{{ is_array($milestone) ? ($milestone['title'] ?? $milestone['name'] ?? '') : $milestone }}</p>@empty<p>Belum ada capaian</p>@endforelse</div>
        </div>
        @if($project->status === 'ongoing')
            @auth
                @if(auth()->id() !== $project->owner_id && ! $project->members->contains(auth()->id()))
                    <form method="POST" action="{{ route('projects.join', $project) }}" class="project-detail-join">@csrf<button class="primary-button" type="submit">Gabung</button></form>
                @endif
            @else
                <a class="primary-button" href="{{ route('login') }}">Gabung</a>
            @endauth
        @endif
        @error('join')<p role="alert">{{ $message }}</p>@enderror
    </article>
</main>
@endsection
