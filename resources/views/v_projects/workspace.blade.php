@extends('v_layouts.app')

@section('title', $project->title . ' - Kelola Projek')

@push('styles')
    @vite('resources/css/projects.css')
@endpush

@section('content')
@include('v_layouts.navbar')

@php
    $milestones = collect($project->milestones ?? []);
    $documents = collect($project->documents ?? []);
    $openRoles = collect($project->roles ?? [])->pluck('name')->filter()->values();
    $status = $project->status === 'ongoing' ? 'SEDANG BERJALAN' : strtoupper((string) $project->status);
@endphp

<main class="workspace-page">
    <section class="workspace-shell">
        <header class="workspace-hero">
            <div class="workspace-hero-top">
                <a href="{{ route('projects') }}" class="workspace-back">← Projek Saya</a>
                <span class="workspace-label">{{ $isOwner ? 'Kelola Projek' : 'Anggota Tim' }}</span>
            </div>
            <h1>{{ $project->title }}</h1>
            <p class="workspace-status">STATUS: {{ $status }}</p>

            <div class="workspace-team" aria-label="Posisi tim yang dibutuhkan">
                @for($position = 0; $position < 3; $position++)
                    <div class="workspace-position">
                        <span class="workspace-avatar workspace-avatar-empty" aria-hidden="true"></span>
                        <small>{{ $openRoles[$position] ?? 'Posisi dibutuhkan' }}</small>
                    </div>
                @endfor
            </div>
        </header>

        @unless($isOwner)
            <p class="workspace-feedback" role="status">Sudah Diterima. Kamu sekarang anggota projek ini.</p>
        @endunless

        @if(session('success'))
            <p class="workspace-feedback" role="status">{{ session('success') }}</p>
        @endif

        <div class="workspace-grid">
            <section class="workspace-card" aria-labelledby="milestoneHeading">
                <h2 id="milestoneHeading">Capaian Tim</h2>
                <div class="workspace-list">
                    @forelse($milestones as $index => $milestone)
                        @php
                            $label = is_array($milestone) ? ($milestone['name'] ?? $milestone['title'] ?? '') : $milestone;
                            $milestoneStatus = is_array($milestone) ? ($milestone['status'] ?? '') : '';
                            $complete = preg_match('/selesai|done|completed/i', (string) $milestoneStatus);
                        @endphp
                        @if(filled($label))
                            @if($isOwner)
                                <form method="POST" action="{{ route('projects.workspace.milestones.toggle', [$project, $index]) }}" class="workspace-check-form">
                                    @csrf
                                    <label class="workspace-check">
                                        <input type="checkbox" @checked($complete) onchange="this.form.submit()" aria-label="Tandai {{ $label }} selesai">
                                        <span>{{ $label }}</span>
                                    </label>
                                </form>
                            @else
                                <span class="workspace-check">
                                    <input type="checkbox" disabled @checked($complete)>
                                    <span>{{ $label }}</span>
                                </span>
                            @endif
                        @endif
                    @empty
                        <p class="workspace-empty">Belum ada capaian.</p>
                    @endforelse
                </div>
                @if($isOwner)
                    <form method="POST" action="{{ route('projects.workspace.milestones.store', $project) }}" class="workspace-inline-form">
                        @csrf
                        <label for="milestone-title" class="sr-only">Judul capaian</label>
                        <input id="milestone-title" name="title" type="text" maxlength="255" required value="{{ old('title') }}" placeholder="Judul capaian">
                        <button type="submit">Tambah Capaian</button>
                    </form>
                    @error('title')<p class="workspace-error">{{ $message }}</p>@enderror
                @endif
            </section>

            <section class="workspace-card" aria-labelledby="documentsHeading">
                <h2 id="documentsHeading">File &amp; Dokumen</h2>
                <div class="workspace-list">
                    @forelse($documents as $document)
                        @php
                            $label = is_array($document) ? ($document['name'] ?? $document['title'] ?? '') : $document;
                            $path = is_array($document) ? ($document['path'] ?? null) : null;
                        @endphp
                        @if(filled($label))
                            @if($path)
                                <a class="workspace-document" href="{{ asset('storage/' . $path) }}" target="_blank" rel="noopener">{{ $label }}</a>
                            @else
                                <p class="workspace-document">{{ $label }}</p>
                            @endif
                        @endif
                    @empty
                        <p class="workspace-empty">Belum ada file atau dokumen.</p>
                    @endforelse
                </div>
                @if($isOwner)
                    <form method="POST" action="{{ route('projects.workspace.documents.store', $project) }}" enctype="multipart/form-data" class="workspace-upload-form">
                        @csrf
                        <input id="project-document" name="document" type="file" class="sr-only" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.png,.jpg,.jpeg,.webp">
                        <label for="project-document" class="workspace-file-picker">Choose File</label>
                        <button type="submit">Upload File</button>
                    </form>
                    @error('document')<p class="workspace-error">{{ $message }}</p>@enderror
                @endif
            </section>

            <section class="workspace-card workspace-chat-card" aria-labelledby="teamChatHeading">
                <h2 id="teamChatHeading">Chat Tim</h2>
                <p>Diskusikan progres projek bersama anggota tim.</p>
                <a href="{{ route('projects.chat', $project) }}" class="workspace-chat-btn">Buka Halaman Chat</a>
            </section>
        </div>

        <footer class="workspace-footer">
            <p>◷ Tenggat Projek: <strong>{{ $project->deadline?->translatedFormat('d F Y') ?? 'Belum ditentukan' }}</strong></p>
        </footer>
    </section>
</main>
@endsection
