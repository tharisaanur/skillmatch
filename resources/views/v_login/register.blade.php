<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar - SkillMatch</title>

    @vite('resources/css/register.css')

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

</head>


<body>

<main class="register-page">

    {{-- BACKGROUND --}}
    <img
        src="{{ asset('images/bg-login.png') }}"
        alt=""
        class="register-background"
    >

    {{-- OVERLAY --}}
    <div class="register-overlay"></div>


    {{-- KONTEN --}}
    <div class="register-content">

        {{-- LOGO --}}
        <a
            href="{{ route('home') }}"
            class="register-logo"
        >
            <img
                src="{{ asset('images/logo.png') }}"
                alt="SkillMatch"
            >
        </a>


        {{-- CARD REGISTER --}}
        <section class="register-card">

            <h1>
                Bergabung dengan
                <span>SkillMatch</span>
            </h1>


            {{-- ERROR --}}
            @if ($errors->any())

                <div class="form-errors">

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </div>

            @endif


            <form
                action="{{ route('register.store') }}"
                method="POST"
            >

                @csrf


                {{-- NAMA --}}
                <div class="form-group">

                    <label for="name">
                        Nama Lengkap
                    </label>

                    <div class="input-wrapper">

                        <i
                            class="fa-regular fa-user input-icon"
                        ></i>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan Nama Lengkap"
                            required
                        >

                    </div>

                </div>


                {{-- EMAIL --}}
                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <div class="input-wrapper">

                        <i
                            class="fa-regular fa-envelope input-icon"
                        ></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan Email"
                            required
                        >

                    </div>

                </div>


                {{-- PASSWORD --}}
                <div class="form-group">

                    <label for="password">
                        Kata Sandi
                    </label>

                    <div class="input-wrapper password-wrapper">

                        <i
                            class="fa-solid fa-lock input-icon"
                        ></i>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            minlength="8"
                            pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}"
                            placeholder="Min. 8 karakter, A-Z, @/#"
                            autocomplete="new-password"
                            required
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


                {{-- KONFIRMASI PASSWORD --}}
                <div class="form-group">

                    <label for="password_confirmation">
                        Konfirmasi Kata Sandi
                    </label>

                    <div class="input-wrapper password-wrapper">

                        <i
                            class="fa-solid fa-lock input-icon"
                        ></i>


                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            minlength="8"
                            placeholder="Ulangi Kata Sandi"
                            autocomplete="new-password"
                            required
                        >


                        <button
                            type="button"
                            class="toggle-password"
                            data-password-toggle="password_confirmation"
                            aria-label="Tampilkan kata sandi"
                            aria-pressed="false"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>

                    </div>

                </div>


                {{-- BUTTON --}}
                <div class="register-actions">

                    <a
                        href="{{ route('login') }}"
                        class="btn-back"
                    >
                        Kembali
                    </a>


                    <button
                        type="submit"
                        class="btn-register"
                    >
                        Daftar
                    </button>

                </div>

            </form>


            {{-- FOOTER --}}
            <div class="register-footer">

                <span>
                    Sudah Punya Akun?
                </span>

                <a href="{{ route('login') }}">

                    Masuk Sekarang

                    <i
                        class="fa-solid fa-arrow-right"
                    ></i>

                </a>

            </div>

        </section>

    </div>

</main>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const buttons =
            document.querySelectorAll(
                '[data-password-toggle]'
            );


        buttons.forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const target =
                        button.dataset.passwordToggle;

                    const input =
                        document.getElementById(target);

                    const icon =
                        button.querySelector('i');


                    if (!input || !icon) {
                        return;
                    }


                    if (input.type === 'password') {

                        input.type = 'text';

                        icon.classList.remove(
                            'fa-eye'
                        );

                        icon.classList.add(
                            'fa-eye-slash'
                        );

                    } else {

                        input.type = 'password';

                        icon.classList.remove(
                            'fa-eye-slash'
                        );

                        icon.classList.add(
                            'fa-eye'
                        );

                    }

                }
            );

        });

    }
);

</script>
@vite('resources/js/auth.js')
</body>
</html>