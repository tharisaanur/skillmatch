@extends('v_layouts.app')

@section('title', 'Rekomendasi Projek - SkillMatch')

@push('styles')
    @vite('resources/css/explore.css')

    <style>
        .explore-page {
            background: 
                linear-gradient(
                    90deg,
                    rgba(4, 52, 76, 0.72) 0%,
                    rgba(4, 52, 76, 0.48) 50%,
                    rgba(4, 52, 76, 0.68) 100%
                ),
                url("{{ asset('images/bg.png') }}") center center / cover no-repeat fixed !important;
        }
    </style>
@endpush

@section('content')
@include('v_layouts.navbar')

{{-- ================================================================= --}}
{{-- DATA PROJEK (Ubah / Tambah Isinya Di Sini) --}}
{{-- ================================================================= --}}
@php
    $projects = [
        [
            'title' => 'AI Deteksi Sampah ',
            'description' => 'Membangun model AI untuk mengenali jenis sampah melalui kamera',
            'owner' => 'Tharisha Nur Fadhilah',
            'avatar' => asset('images/riri.png'),
            'skills' => ['Python, Laravel', 'MySQL', 'Figma'],
            'members' => '0/3',
            'deadline' => '20 November 2026',
        ],
        [
            'title' => 'Kampanye Edukasi Gizi Remaja',
            'description' => 'Membuat kampanye edukasi tentang pola makan sehat untuk mahasiswa dan remaja melalui poster, booklet, dan media sosial.',
            'owner' => 'Jesen Saputra',
            'avatar' => asset('images/jesen.png'),
            'skills' => ['Desain Grafis', 'DKV', 'Copywriting'],
            'members' => '0/4',
            'deadline' => '28 Oktober 2026',
        ],
        [
            'title' => 'Majalah Digital Budaya',
            'description' => 'Membuat majalah digital berisi cerita budaya, tradisi, kuliner, dan tokoh lokal',
            'owner' => 'Tharisha Nur Fadhilah',
            'avatar' => asset('images/riri.png'),
            'skills' => ['Fotografi', 'Editing', 'Ilustrasi'],
            'members' => '0/3',
            'deadline' => '20 Oktober 2026',
        ],
        [
            'title' => 'Branding Coffee Shop Lokal',
            'description' => 'Membuat identitas visual dan media promosi digital untuk UMKM coffee shop.',
            'owner' => 'Jesen Saputra',
            'avatar' => asset('images/jesen.png'),
            'skills' => ['Figma', 'Illustrator', 'Branding'],
            'members' => '0/3',
            'deadline' => '15 Desember 2026',
        ],
    ];
@endphp

<main class="explore-page">
    <section class="explore-hero">
        <p class="explore-eyebrow">Projek aktif untuk skillmu</p>
        <h1>Temukan Projek<br><span>yang Membutuhkan</span> Skillmu</h1>
        <p>Jelajahi projek aktif berdasarkan peran yang sesuai dengan skill di profilmu.</p>

        <form class="explore-search" method="GET" action="#">
            <input name="q" placeholder="Cari projek, peran, atau pemilik" aria-label="Cari projek atau peran">
            <button type="submit" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </section>

    <section class="explore-results" aria-labelledby="result-title">
        <div class="section-heading">
            <div>
                <p class="explore-eyebrow">Berdasarkan skill profilmu</p>
                <h2 id="result-title">Rekomendasi Untukmu</h2>
            </div>
            <span>{{ count($projects) }} projek tersedia</span>
        </div>

        <div class="member-grid project-grid">

            {{-- Loop Otomatis Menampilkan Card Projek --}}
            @foreach ($projects as $project)
                <article class="member-card project-card">
                    <div class="member-card-top">
                        <span class="availability"><i></i> Dibuka</span>
                        <div class="skill-tags">
                            @foreach ($project['skills'] as $skill)
                                <span>{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="member-identity">
                        <div>
                            <h3>{{ $project['title'] }}</h3>
                            <p class="member-university">{{ $project['description'] }}</p>
                        </div>
                    </div>

                    <div class="project-owner">
                        <img src="{{ $project['avatar'] }}" 
                             onerror="this.style.display='none'" 
                             alt="{{ $project['owner'] }}" 
                             class="owner-avatar">
                        <span>{{ $project['owner'] }}</span>
                    </div>

                    <div class="project-progress-wrapper">
                        <div class="project-progress-label">
                            <span>Skill match</span>
                            <strong class="match-text">0%</strong>
                        </div>
                        <progress class="project-progress match-bar" value="0" max="100"></progress>
                    </div>

                    <div class="project-meta">
                        <span class="meta-members"><i class="fa-solid fa-users"></i> {{ $project['members'] }}</span>
                        <span class="meta-deadline"><i class="fa-regular fa-calendar"></i> {{ $project['deadline'] }}</span>
                    </div>

                    <div class="member-actions">
                        <a class="outline-button" href="{{ url('/v_explore/detail') }}">Lihat Detail</a>
                        <button class="primary-button" type="button">Gabung</button>
                    </div>
                </article>
            @endforeach

        </div>
    </section>
</main>

{{-- Script Hitung Skill Match Otomatis --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Simpan/ambil skill user dari LocalStorage
    let userSkills = JSON.parse(localStorage.getItem('userSkills')) || ['Laravel', 'Figma', 'UI/UX'];
    userSkills = userSkills.map(s => s.toLowerCase().trim());

    // 2. Hitung persentase match untuk tiap card secara otomatis
    document.querySelectorAll('.project-card').forEach(card => {
        const tagElements = card.querySelectorAll('.skill-tags span');
        const projectSkills = Array.from(tagElements).map(el => el.textContent.toLowerCase().trim());

        if (projectSkills.length === 0) return;

        let matchCount = 0;
        projectSkills.forEach(skill => {
            if (userSkills.includes(skill)) {
                matchCount++;
            }
        });

        const percentage = Math.round((matchCount / projectSkills.length) * 100);

        const textElement = card.querySelector('.match-text');
        const barElement = card.querySelector('.match-bar');

        if (textElement) textElement.textContent = percentage + '%';
        if (barElement) barElement.value = percentage;
    });
});
</script>
@endsection