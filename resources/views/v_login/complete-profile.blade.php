<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profile - SkillMatch</title>

    @vite('resources/css/complete-profile.css')

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >
</head>

<body>

<main class="complete-page">

    {{-- Background --}}
    <img
        src="{{ asset('images/bg-login.png') }}"
        alt=""
        class="complete-background"
    >

    <div class="complete-overlay"></div>

    <div class="complete-content">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="complete-logo">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="SkillMatch"
            >
        </a>

        {{-- Card --}}
        <section class="complete-card">

            <h1>{{ ($editingSkills ?? false) ? 'Kelola Keahlian Utama' : 'Lengkapi Profile Kamu' }}</h1>

            @if(session('success'))
                <div class="success-alert" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @unless($editingSkills ?? false)<h2>Selamat Registrasi Berhasil.</h2>@endunless

            <p class="complete-desc">
                @if($editingSkills ?? false)
                    Pilih keahlian yang ingin ditampilkan di profilmu.
                @else
                    Tingkatkan kesempatan anda: Centang Skill anda untuk
                    dicocokan dengan projek yang tepat.
                @endif
            </p>

            <form action="{{ route('complete-profile.store') }}" method="POST">
                @csrf
                
                {{-- Penanda penentu arah submit (Daftar Awal vs Edit Profil) --}}
                @if($editingSkills ?? false)
                    <input type="hidden" name="from_profile" value="1">
                @endif

                <div class="skill-grid">
                    @include('v_login.skill-options', ['selectedSkills' => Auth::user()->skills ?? [], 'customSkills' => $editingSkills ?? false])
                </div>

                <div class="other-skill-wrapper">
                    <input
                        type="text"
                        name="other_skill"
                        class="other-skill"
                        placeholder="Skill Lainnya .."
                    >
                </div>

                <div class="complete-actions">
                    {{-- Tombol Batal / Lewati --}}
                    <a href="{{ ($editingSkills ?? false) ? route('profile.edit') : route('login') }}" class="btn-skip">
                        {{ ($editingSkills ?? false) ? 'Batal' : 'Lewati' }}
                    </a>

                    {{-- Simpan & Lanjut --}}
                    <button type="submit" class="btn-save">
                        Simpan & Lanjut
                    </button>
                </div>

            </form>

        </section>

    </div>

</main>

</body>
</html>