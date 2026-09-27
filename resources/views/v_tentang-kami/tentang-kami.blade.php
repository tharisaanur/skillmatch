@extends('v_layouts.app')

@section('title', 'SkillMatch - Tentang Kami')

@push('styles')
    @vite('resources/css/hero.css')
    @vite('resources/css/tentang-kami.css')
@endpush

@section('content')
<!-- NAVBAR HERO -->
@include('v_layouts.navbar')

<!-- HERO WRAPPER DENGAN BACKGROUND BERANDA -->
<main>
    <section class="hero-section" style="background-image: url('{{ asset('images/bg.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" aria-labelledby="about-title">
        <div class="hero-overlay"></div>

        <div class="tentang-kami-page">
            <div class="tentang-kami-container">
                
                <!-- KOLOM KIRI: ACCORDION CARD -->
                <div class="faq-box">
                    <details class="faq-item">
                        <summary class="faq-title">
                            <span>Apakah SkillMatch khusus untuk mahasiswa satu kampus saja?</span>
                            <span class="chevron-icon"></span>
                        </summary>
                        <div class="faq-desc">
                            <p>Tidak, SkillMatch dirancang untuk memfasilitasi kolaborasi mahasiswa baik dalam satu kampus maupun lintas perguruan tinggi.</p>
                        </div>
                    </details>

                    <details class="faq-item">
                        <summary class="faq-title">
                            <span>Apakah pembuatan proyek di SkillMatch dipungut biaya?</span>
                            <span class="chevron-icon"></span>
                        </summary>
                        <div class="faq-desc">
                            <p>Tidak, pembuatan dan pendaftaran proyek di SkillMatch sepenuhnya gratis untuk seluruh mahasiswa.</p>
                        </div>
                    </details>

                    <details class="faq-item">
                        <summary class="faq-title">
                            <span>Siapa yang dapat melihat profile dan portofolio saya?</span>
                            <span class="chevron-icon"></span>
                        </summary>
                        <div class="faq-desc">
                            <p>Profile dan portofolio Anda dapat dilihat oleh pengguna terdaftar dan pembuat proyek yang sedang mencari anggota tim.</p>
                        </div>
                    </details>

                    <details class="faq-item">
                        <summary class="faq-title">
                            <span>Apakah saya bisa membatalkan lamaran pojek yang sudah saya kirim?</span>
                            <span class="chevron-icon"></span>
                        </summary>
                        <div class="faq-desc">
                            <p>Ya, Anda dapat membatalkan lamaran proyek melalui halaman "Projek Saya" selama proses seleksi belum ditutup oleh pemilik proyek.</p>
                        </div>
                    </details>

                    <details class="faq-item">
                        <summary class="faq-title">
                            <span>Bagaimana jika saya tidak memiliki pengalaman projek sebelumnya?</span>
                            <span class="chevron-icon"></span>
                        </summary>
                        <div class="faq-desc">
                            <p>Tidak masalah! SkillMatch hadir untuk membantu Anda membangun portofolio awal dengan menemukan proyek yang sesuai dengan tingkat keahlian Anda.</p>
                        </div>
                    </details>
                </div>

                <!-- KOLOM KANAN: DESKRIPSI -->
                <div class="about-box">
                    <h2 id="about-title" class="about-title">APA ITU SKILL <span class="match-text">MATCH</span> ?</h2>
                    <div class="about-card">
                        <p><strong>SkillMatch</strong> adalah platform yang membantu mahasiswa menemukan rekan proyek berdasarkan keahlian, minat, dan kebutuhan tim. Kami hadir untuk mempermudah proses kolaborasi sehingga setiap mahasiswa dapat membangun tim yang lebih tepat, produktif, dan siap menghasilkan karya terbaik.</p>
                    </div>
                </div>

            </div>

            <div class="copyright-footer">
                © 2026 SkillMatch
            </div>
        </div>
    </section>
</main>
@endsection