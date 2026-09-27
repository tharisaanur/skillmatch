@extends('v_layouts.app')

@section('title', 'SkillMatch - Kolaborasi Mahasiswa')

@push('styles')
    @vite('resources/css/hero.css')
@endpush

@section('content')
@include('v_layouts.navbar')

<main>
   <section class="hero-section" style="background-image: url('{{ asset('images/bg.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" aria-labelledby="hero-title">
        <div class="hero-overlay"></div>

        <div class="hero-shell">
            <div class="hero-copy">
                <p class="eyebrow">Temukan partner yang tepat untuk ide terbaikmu</p>
                <h1 id="hero-title">
                    Setiap Ide Hebat<br>
                    Dimulai dari<br>
                    <span>Kolaborasi</span> yang Tepat
                </h1>

                <p class="hero-description">
                    SkillMatch mempertemukan mahasiswa dengan keahlian yang saling melengkapi agar proses kolaborasi menjadi lebih efektif, produktif, dan menyenangkan.
                </p>

                <a class="primary-cta" href="{{ auth()->check() ? route('explore') : route('login') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg>
                    Temukan Tim Projek
                </a>

                <div class="benefit-grid" aria-label="Keunggulan SkillMatch">
                    {{-- Benefit 1: Temukan Rekan --}}
                    <article class="benefit-item">
                        <div class="circle-layer-outer">
                            <div class="circle-layer-inner">
                                <i class="fa-solid fa-user-group icon-style"></i>
                            </div>
                        </div>
                        <div class="benefit-text">
                            <h2>Temukan Rekan</h2>
                            <p>Cari rekan sesuai keahlianmu</p>
                        </div>
                    </article>

                    {{-- Benefit 2: Kolaborasi Efektif --}}
                    <article class="benefit-item">
                        <div class="circle-layer-outer">
                            <div class="circle-layer-inner">
                                <i class="fa-solid fa-puzzle-piece icon-style"></i>
                            </div>
                        </div>
                        <div class="benefit-text">
                            <h2>Kolaborasi Efektif</h2>
                            <p>Kerja sama jadi lebih mudah dan terarah</p>
                        </div>
                    </article>

                    {{-- Benefit 3: Wujudkan Ide --}}
                    <article class="benefit-item">
                        <div class="circle-layer-outer">
                            <div class="circle-layer-inner">
                                <i class="fa-solid fa-rocket icon-style"></i>
                            </div>
                        </div>
                        <div class="benefit-text">
                            <h2>Wujudkan Ide</h2>
                            <p>Bangun projek dan capai tujuan bersama</p>
                        </div>
                    </article>
                </div>
            </div>

            <div class="hero-visual" aria-hidden="true">
                <div class="mascot-glow"></div>
                <img src="{{ asset('images/maskot.png') }}" alt="Maskot SkillMatch" class="mascot">
            </div>
        </div>

        <div class="stats-section" id="jelajahi">
            <h2>STATISTIK <span>SKILLMATCH</span></h2>
            <div class="stats-grid">
                <article class="stat-card">
                    <div class="stat-circle">
                        <strong class="counter" data-target="{{ $stats['active_users'] }}">{{ $stats['active_users'] }}</strong>
                        <span>MAHASISWA</span>
                    </div>
                    <p>Pengguna Aktif</p>
                </article>
                <article class="stat-card">
                    <div class="stat-circle">
                        <strong class="counter" data-target="{{ $stats['projects'] }}">{{ $stats['projects'] }}</strong>
                        <span>PROYEK</span>
                    </div>
                    <p>Projek Terbentuk</p>
                </article>
                <article class="stat-card">
                    <div class="stat-circle">
                        <strong class="counter" data-target="{{ $stats['study_programs'] }}">{{ $stats['study_programs'] }}</strong>
                        <span>JURUSAN</span>
                    </div>
                    <p>Program Studi</p>
                </article>
            </div>
        </div>
    </section>

    <section class="anchor-placeholder" id="projek-saya" aria-hidden="true"></section>
    <section class="anchor-placeholder" id="masuk" aria-hidden="true"></section>
</main>
@endsection