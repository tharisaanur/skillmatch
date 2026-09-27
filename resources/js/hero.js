document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.main-nav');
    const profile = document.querySelector('.nav-profile');
    const profileTrigger = document.getElementById('profileTrigger');

    const closeProfile = () => {
        profile?.classList.remove('open');
        profileTrigger?.setAttribute('aria-expanded', 'false');
    };

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const opened = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', String(opened));
            if (!opened) closeProfile();
        });

        nav.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                nav.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
                closeProfile();
            });
        });
    }

    if (profile && profileTrigger) {

        profileTrigger.addEventListener('click', (event) => {
            event.stopPropagation();
            const opened = profile.classList.toggle('open');
            profileTrigger.setAttribute('aria-expanded', String(opened));
        });


        document.addEventListener('click', (event) => {

            if (!profile.contains(event.target)) {

                closeProfile();
            }

        });

        profile.addEventListener('focusout', (event) => {
            if (!profile.contains(event.relatedTarget)) closeProfile();
        });

    }

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        if (profile?.classList.contains('open')) {
            closeProfile();
            profileTrigger.focus();
        } else if (nav?.classList.contains('open')) {
            nav.classList.remove('open');
            toggle?.setAttribute('aria-expanded', 'false');
            toggle?.focus();
        }
    });
    const counters = document.querySelectorAll('.counter');
    if (!counters.length) return;

    const runCounter = (el) => {
        const target = Number(el.dataset.target || 0);
        const duration = 1000;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            el.textContent = Math.floor(target * (1 - Math.pow(1 - progress, 3)));
            if (progress < 1) requestAnimationFrame(tick);
            else el.textContent = target;
        };
        requestAnimationFrame(tick);
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    runCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: .5 });
        counters.forEach((counter) => observer.observe(counter));
    } else {
        counters.forEach(runCounter);
    }
});
