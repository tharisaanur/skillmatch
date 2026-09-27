@extends('v_layouts.app')
@section('title', 'Verifikasi Email - SkillMatch')
@push('styles')
    @vite('resources/css/auth.css')
@endpush
@section('content')
<main class="register-page">
    <section class="register-card">
        <h1>Verifikasi <span>Email</span></h1>
        <p>Silakan cek inbox emailmu, lalu klik tautan verifikasi untuk mengaktifkan akun.</p>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn-register" type="submit">Kirim Ulang Email</button>
        </form>
    </section>
</main>
@endsection
