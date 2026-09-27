document.addEventListener('DOMContentLoaded', function () {

    const buttons =
        document.querySelectorAll('[data-password-toggle]');

    buttons.forEach(function (button) {

        button.addEventListener('click', function () {

            const targetId =
                button.dataset.passwordToggle;

            const input =
                document.getElementById(targetId);

            const icon =
                button.querySelector('i');

            if (!input || !icon) {
                return;
            }

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');

                button.setAttribute(
                    'aria-pressed',
                    'true'
                );

                button.setAttribute(
                    'aria-label',
                    'Sembunyikan kata sandi'
                );

            } else {

                input.type = 'password';

                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');

                button.setAttribute(
                    'aria-pressed',
                    'false'
                );

                button.setAttribute(
                    'aria-label',
                    'Tampilkan kata sandi'
                );

            }

        });

    });

});