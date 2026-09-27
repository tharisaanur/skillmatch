<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="theme-color"
        content="#04344C"
    >

    <title>
        @yield('title', 'SkillMatch')
    </title>

    {{-- FAVICON --}}
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo.png') }}"
    >

    {{-- FONT AWESOME --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    {{-- CSS GLOBAL --}}
    @vite('resources/css/global.css')
    @unless(request()->routeIs('login'))
        @vite('resources/css/theme.css')
    @endunless

    {{-- CSS KHUSUS HALAMAN --}}
    @stack('styles')

</head>

<body>

    {{-- INI WAJIB ADA --}}
    @yield('content')


    {{-- JS KHUSUS HALAMAN --}}
    @vite('resources/js/hero.js')
    @stack('scripts')

</body>

</html>