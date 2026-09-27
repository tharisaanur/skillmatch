<header class="site-header" id="beranda">

    <div class="nav-shell">

        {{-- LOGO --}}
        <a
            class="brand"
            href="{{ route('home') }}"
            aria-label="SkillMatch Beranda"
        >
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo SkillMatch"
            >
        </a>


        {{-- MOBILE TOGGLE --}}
        <button
            class="nav-toggle"
            type="button"
            aria-label="Buka menu"
            aria-expanded="false"
            aria-controls="main-nav"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>


        {{-- NAVIGATION --}}
        <nav
            class="main-nav"
            id="main-nav"
            aria-label="Navigasi utama"
        >

            {{-- BERANDA --}}
            <a
                href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'active' : '' }}"
                @if(request()->routeIs('home'))
                    aria-current="page"
                @endif
            >
                Beranda
            </a>


            {{-- JELAJAHI PROJEK --}}
            <a
                href="{{ auth()->check()
                    ? route('explore')
                    : route('login') }}"
                class="{{
                    request()->routeIs('explore') ||
                    request()->routeIs('explore.project') ||
                    request()->routeIs('member.show')
                        ? 'active'
                        : ''
                }}"
            >
                Jelajahi Projek
            </a>


            {{-- PROJEK SAYA --}}
            <a
                href="{{ auth()->check()
                    ? route('projects')
                    : route('login') }}"
                class="{{ request()->routeIs('projects*') ? 'active' : '' }}"
            >
                Projek Saya
            </a>


            {{-- TENTANG KAMI --}}
            <a
                href="{{ route('about') }}"
                class="{{ request()->routeIs('about') ? 'active' : '' }}"
                @if(request()->routeIs('about'))
                    aria-current="page"
                @endif
            >
                Tentang Kami
            </a>



            {{-- =========================
                USER SUDAH LOGIN
            ========================== --}}

            @auth

                @php
                    $navUser = auth()->user();

                    $navPhoto = $navUser->profile_photo
                        ? asset(
                            'storage/' .
                            $navUser->profile_photo
                        )
                        : null;


                    /*
                    |--------------------------------------------------------------------------
                    | JUMLAH NOTIFIKASI
                    |--------------------------------------------------------------------------
                    */

                    $notificationQuery =
                        \App\Models\TeamRequest::where(
                            'recipient_id',
                            auth()->id()
                        )
                        ->whereNotNull('project_id');


                    if (
                        \Illuminate\Support\Facades\Schema::hasColumn(
                            'team_requests',
                            'read_at'
                        )
                    ) {

                        $notificationCount =
                            $notificationQuery
                                ->whereNull('read_at')
                                ->count();

                    } else {

                        $notificationCount =
                            $notificationQuery
                                ->where('status', 'pending')
                                ->count();

                    }
                @endphp



                {{-- =========================================
                    ACTION KANAN: NOTIFIKASI + PROFIL
                ========================================== --}}

                <div class="navbar-actions">


                    {{-- NOTIFIKASI --}}
                    <a
                        href="{{ route('notifications.index') }}"
                        class="navbar-notification {{
                            request()->routeIs('notifications.*')
                                ? 'active'
                                : ''
                        }}"
                        aria-label="Notifikasi"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            width="22"
                            height="22"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path
                                d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"
                            ></path>

                            <path
                                d="M13.73 21a2 2 0 0 1-3.46 0"
                            ></path>
                        </svg>


                        @if($notificationCount > 0)

                            <span class="navbar-notification-count">

                                {{
                                    $notificationCount > 9
                                        ? '9+'
                                        : $notificationCount
                                }}

                            </span>

                        @endif

                    </a>



                    {{-- PROFILE --}}
                    <div class="nav-profile">

                        {{-- PROFILE TRIGGER --}}
                        <button
                            class="profile-trigger"
                            id="profileTrigger"
                            type="button"
                            data-profile-url="{{ route('profile') }}"
                            aria-expanded="false"
                            aria-controls="profileDropdown"
                            aria-label="Buka menu profil {{ $navUser->name }}"
                        >

                            {{-- FOTO --}}
                            @if($navPhoto)

                                <img
                                    src="{{ $navPhoto }}"
                                    alt="Foto {{ $navUser->name }}"
                                    class="profile-avatar-img"
                                >

                            @else

                                <img
                                    src="{{ asset('images/logo-avatar.png') }}"
                                    alt=""
                                    class="profile-avatar-img"
                                >

                            @endif


                            {{-- NAMA USER --}}
                            <span class="profile-name">
                                {{ $navUser->name }}
                            </span>


                            {{-- ICON DROPDOWN --}}
                            <svg
                                class="profile-chevron"
                                width="12"
                                height="12"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path d="m6 9 6 6 6-6" />
                            </svg>

                        </button>



                        {{-- DROPDOWN --}}
                        <div
                            class="profile-dropdown"
                            id="profileDropdown"
                        >

                            {{-- USER INFO --}}
                            <div class="profile-dropdown-user">

                                <strong>
                                    {{ $navUser->name }}
                                </strong>

                                <small>
                                    {{ $navUser->email }}
                                </small>

                            </div>


                            {{-- PROFIL --}}
                            <a
                                class="profile-dropdown-link"
                                href="{{ route('profile') }}"
                                @if(request()->routeIs('profile'))
                                    aria-current="page"
                                @endif
                            >
                                Profil Saya
                            </a>


                            {{-- PROJEK --}}
                            <a
                                class="profile-dropdown-link"
                                href="{{ route('projects') }}"
                                @if(request()->routeIs('projects*'))
                                    aria-current="page"
                                @endif
                            >
                                Projek Saya
                            </a>


                            {{-- EDIT PROFIL --}}
                            <a
                                class="profile-dropdown-link"
                                href="{{ route('profile.edit') }}"
                                @if(request()->routeIs('profile.edit'))
                                    aria-current="page"
                                @endif
                            >
                                Edit Profil
                            </a>


                            {{-- LOGOUT --}}
                            <form
                                action="{{ route('logout') }}"
                                method="POST"
                            >

                                @csrf

                                <button
                                    class="profile-logout"
                                    type="submit"
                                >
                                    Keluar
                                </button>

                            </form>

                        </div>

                    </div>

                </div>


            {{-- =========================
                USER BELUM LOGIN
            ========================== --}}

            @else

                <a
                    href="{{ route('login') }}"
                    class="nav-item nav-auth"
                >
                    Daftar / Masuk
                </a>

            @endauth

        </nav>

    </div>

</header>