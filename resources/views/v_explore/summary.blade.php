@php
    $summaryOwner = $project->owner;
    $summaryName = $summaryOwner?->name ?? 'Pengguna';

    $summaryPhoto = $summaryOwner?->profile_photo
        ? asset('storage/' . $summaryOwner->profile_photo)
        : null;

    $summaryDeadline = $project->deadline
        ? $project->deadline->locale('id')->translatedFormat('d F Y')
        : 'Belum ditentukan';
@endphp

<div class="sm-summary">
    <h3>{{ $project->title }}</h3>

    <p class="sm-description">{{ $project->description }}</p>

    <div class="sm-owner">
        @if($summaryPhoto)
            <img src="{{ $summaryPhoto }}" alt="Foto {{ $summaryName }}">
        @else
            <span class="sm-avatar" aria-hidden="true">
                {{ mb_strtoupper(mb_substr($summaryName, 0, 1)) }}
            </span>
        @endif

        <span>{{ $summaryName }}</span>
    </div>

    <div class="sm-match">
        <p>Kecocokan keahlian</p>

        <div class="sm-match-row">
            <div class="sm-track" aria-hidden="true">
                <span style="width: 0%"></span>
            </div>

            <small>Belum dihitung</small>
        </div>
    </div>

    <div class="sm-meta">
        <span>
            <i class="fa-solid fa-user-group" aria-hidden="true"></i>
            {{ $project->members->count() }} anggota
        </span>

        <span>
            <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
            {{ $summaryDeadline }}
        </span>
    </div>
</div>