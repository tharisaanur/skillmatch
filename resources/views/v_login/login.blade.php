@extends('v_layouts.app')

@section('title', 'SkillMatch - Masuk')

@push('styles')
    @vite('resources/css/login.css')
@endpush

@section('content')
<div class="login">
    <!-- BACKGROUND & LOGO -->
    <img class="bg-login-icon" alt="" src="{{ asset('images/bg-login.png') }}">
    <a href="{{ url('/') }}">
        <img class="logo-icon" alt="SkillMatch Logo" src="{{ asset('images/logo.png') }}">
    </a>

    <!-- CARD BACKDROP -->
    <div class="login-child"></div>

    <!-- JUDUL -->
    <div class="masuk-ke-skillmatch-container">
        <span class="masuk-ke-skillmatch-container2">
            <span class="span">Masuk ke </span>
            <span class="match">SkillMatch</span>
        </span>
    </div>

    <!-- FORM LOGIN -->
    <form action="{{ route('login') }}" method="POST">
        @csrf

        {{-- PESAN ERROR --}}
        @if(session('error') || $errors->any())
            <div class="form-errors">
                <p>{{ session('error') ?? $errors->first() }}</p>
            </div>
        @endif

        <div class="form-group">
            <label for="email">Masukkan Email</label>
            <div class="input-wrapper">
                <span class="field-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan email" autofocus>
            </div>
        </div>

        <div class="form-group">

    <label for="password">
        Masukkan Kata Sandi
    </label>

    <div class="input-wrapper password-wrapper">

        <span class="field-icon">

            <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <rect
                    x="3"
                    y="11"
                    width="18"
                    height="11"
                    rx="2"
                    ry="2"
                ></rect>

                <path
                    d="M7 11V7a5 5 0 0 1 10 0v4"
                ></path>
            </svg>

        </span>


        <input
            type="password"
            id="password"
            name="password"
            required
            placeholder="Masukkan kata sandi"
        >


        <button
            type="button"
            class="toggle-password"
            data-password-toggle="password"
            aria-label="Tampilkan kata sandi"
            aria-pressed="false"
        >
            <i class="fa-regular fa-eye"></i>
        </button>

    </div>

</div>

        <a href="#" class="lupa-kata-sandi">Lupa Kata Sandi?</a>

        <!-- TOMBOL KEMBALI & MASUK -->
        <a href="{{ url('/') }}" class="login-child2">Kembali</a>
        <button type="submit" class="rectangle-div">Masuk</button>

        <!-- WRAPPER DAFTAR SEKARANG -->
        <div class="register-bottom-wrapper">
            <span>Belum Punya Akun?</span>
            <a href="{{ route('register') }}" class="daftar-sekarang-link">Daftar Sekarang</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    @vite('resources/js/auth.js')
@endpush