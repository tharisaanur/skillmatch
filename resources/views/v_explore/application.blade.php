@extends('v_layouts.app')

@section('title', 'Status Permintaan - SkillMatch')

@push('styles')
    @vite('resources/css/detail.css')
@endpush

@section('content')
@include('v_layouts.navbar')

<main class="detail-page-wrapper">
    <section class="detail-card application-status-card" aria-labelledby="applicationTitle">
        <p class="application-eyebrow">Status Permintaan Bergabung</p>
        <h1 id="applicationTitle">{{ $project->title }}</h1>

        @if($application->status === 'pending')
            <p class="application-state">Belum Diterima</p>
            <p>Permintaanmu sudah dikirim. Tunggu persetujuan pemilik projek.</p>
        @else
            <p class="application-state">Permintaan Ditolak</p>
            <p>Pemilik projek belum menerima permintaan bergabungmu.</p>
        @endif

        <div class="application-links">
            @if($application->status === 'pending')
                <a class="kembali" href="{{ route('explore.application', $project) }}">Periksa Status</a>
            @endif
            <a class="kembali" href="{{ route('explore.detail', $project) }}">Lihat Detail Projek</a>
            <a class="kembali" href="{{ route('explore') }}">Jelajahi Projek</a>
        </div>
    </section>
</main>
@endsection
