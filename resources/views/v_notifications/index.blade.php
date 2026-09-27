@extends('v_layouts.app')

@section('title', 'Notifikasi - SkillMatch')


@push('styles')
    @vite('resources/css/hero.css')
    @vite('resources/css/notifications.css')
@endpush


@section('content')

@include('v_layouts.navbar')


<main class="notification-page">

    <section class="notification-container">


        {{-- HEADER --}}
        <div class="notification-header">

            <div>

                <h1>
                    Notifikasi
                </h1>

                <p>
                    Lihat permintaan bergabung ke projekmu.
                </p>

            </div>


            @if($unreadCount > 0)

                <span class="notification-new-badge">

                    {{ $unreadCount }}
                    Notifikasi Baru

                </span>

            @endif

        </div>


        {{-- SUCCESS --}}
        @if(session('success'))

            <div class="notification-success">
                {{ session('success') }}
            </div>

        @endif


        <div class="notification-divider"></div>


        {{-- LIST --}}
        <div class="notification-list">

            @forelse($notifications as $notification)

                @php

                    $sender =
                        $notification->sender;

                    $project =
                        $notification->project;

                    $photo =
                        $sender?->profile_photo
                            ? asset(
                                'storage/' .
                                $sender->profile_photo
                            )
                            : asset(
                                'images/profile-user.png'
                            );

                @endphp


                <a
                    href="{{ route(
                        'notifications.show',
                        $notification
                    ) }}"
                    class="
                        notification-card
                        {{ is_null($notification->read_at)
                            ? 'is-unread'
                            : ''
                        }}
                    "
                >

                    {{-- FOTO --}}
                    <img
                        src="{{ $photo }}"
                        alt="{{ $sender?->name }}"
                        class="notification-avatar"
                    >


                    {{-- CONTENT --}}
                    <div class="notification-info">

                        <h2>
                            {{ $sender?->name ?? 'Pengguna' }}
                        </h2>


                        <p>

                            Ingin bergabung ke:

                            <strong>
                                {{ $project?->title ?? '-' }}
                            </strong>

                        </p>


                        <small>

                            {{ $notification
                                ->created_at
                                ->diffForHumans()
                            }}

                        </small>

                    </div>


                    {{-- STATUS --}}
                    <div class="notification-side">

                        @if(
                            is_null(
                                $notification->read_at
                            )
                        )

                            <span
                                class="notification-dot"
                                title="Belum dibaca"
                            ></span>

                        @endif


                        @if(
                            $notification->status
                            === 'accepted'
                        )

                            <span class="status-small accepted">
                                Diterima
                            </span>

                        @elseif(
                            $notification->status
                            === 'rejected'
                        )

                            <span class="status-small rejected">
                                Ditolak
                            </span>

                        @endif

                    </div>

                </a>


            @empty

                <div class="notification-empty">

                    <h2>
                        Belum ada notifikasi
                    </h2>

                    <p>
                        Permintaan bergabung ke projekmu
                        akan muncul di sini.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</main>

@endsection