@extends('v_layouts.app')

@section('title', 'Buat Projek Baru - SkillMatch')

@push('styles')
    @vite('resources/css/hero.css')

    <style>
        .pc-page,
        .pc-page * {
            box-sizing: border-box;
        }

        .pc-page {
            min-height: calc(100vh - var(--header-height, 80px));
            padding: 28px 24px 50px;
            background: linear-gradient(
                110deg,
                #04344c 0%,
                #12485e 58%,
                #33768e 100%
            );
            color: #fff;
        }

        .pc-container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .pc-heading {
            margin-bottom: 18px;
            text-align: center;
        }

        .pc-heading h1 {
            margin: 0 0 10px;
            color: #fff;
            font-size: clamp(26px, 3vw, 34px);
            font-weight: 700;
            line-height: 1.25;
        }

        .pc-heading p {
            margin: 0;
            color: #fff;
            font-size: 14px;
        }

        .pc-form {
            padding: 22px 28px;
            border: 1px solid rgba(213, 245, 252, .85);
            border-radius: 26px;
            background: linear-gradient(
                125deg,
                rgba(143, 206, 224, .25),
                rgba(15, 66, 85, .22) 50%,
                rgba(155, 219, 233, .20)
            );
        }

        .pc-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 60px;
        }

        .pc-field {
            display: block;
            min-width: 0;
        }

        .pc-field > span,
        .pc-section-heading h2 {
            display: block;
            margin: 0 0 8px;
            color: #b0edf9;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
        }

        .pc-description {
            margin-top: 12px;
        }

        .pc-form input,
        .pc-form textarea {
            display: block;
            width: 100%;
            min-width: 0;
            min-height: 38px;
            margin: 0;
            padding: 9px 10px;
            border: 1px solid #c5e7f0;
            border-radius: 9px;
            background: #06364c;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            line-height: 1.4;
        }

        .pc-form input::placeholder,
        .pc-form textarea::placeholder {
            color: #aac6d2;
            opacity: 1;
        }

        .pc-form input[type="date"] {
            color-scheme: dark;
        }

        .pc-form textarea {
            min-height: 60px;
            resize: vertical;
        }

        .pc-form input:focus,
        .pc-form textarea:focus {
            outline: 2px solid #b0edf9;
            outline-offset: 2px;
        }

        .pc-section {
            margin-top: 10px;
        }

        .pc-section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 6px;
        }

        .pc-section-heading h2 {
            margin: 0;
        }

        .pc-add {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 175px;
            min-height: 34px;
            padding: 5px 14px;
            border: 1px solid #d5f5fc;
            border-radius: 9px;
            background: #06364c;
            color: #b0edf9;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .pc-list {
            display: grid;
            gap: 9px;
        }

        .pc-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 38px;
            align-items: start;
            gap: 16px;
        }

        .pc-role-row {
            grid-template-columns:
                minmax(0, 1fr)
                minmax(0, 1.85fr)
                38px;
            gap: 20px;
        }

        .pc-remove {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            padding: 0;
            border: 1px solid #efcbd3;
            border-radius: 9px;
            background: #865363;
            color: #fff;
            font-size: 17px;
            cursor: pointer;
        }

        .pc-remove:hover {
            background: #a25c70;
        }

        .pc-add:hover {
            background: #0d4c64;
        }

        .pc-hint {
            margin: 7px 0 0;
            color: #c5dde5;
            font-size: 12px;
            line-height: 1.4;
        }

        .pc-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
            margin-top: 18px;
        }

        .pc-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 160px;
            min-height: 40px;
            padding: 8px 14px;
            border: 1px solid #d5f5fc;
            border-radius: 9px;
            background: #06364c;
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .pc-submit {
            background: #b0edf9;
            color: #073c53;
        }

        .pc-submit:hover {
            background: #d3f7fc;
        }

        .pc-submit:disabled {
            opacity: .65;
            cursor: wait;
        }

        .pc-errors {
            margin-bottom: 16px;
            padding: 12px 16px;
            border: 1px solid #ffb4b4;
            border-radius: 12px;
            background: rgba(160, 40, 55, .25);
            color: #fff;
        }

        .pc-errors ul {
            margin: 0;
            padding-left: 20px;
        }

        .pc-page button:focus-visible,
        .pc-page a:focus-visible {
            outline: 3px solid #b0edf9;
            outline-offset: 3px;
        }

        @media (max-width: 700px) {
            .pc-page {
                padding: 24px 16px 40px;
            }

            .pc-form {
                padding: 20px 16px;
            }

            .pc-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .pc-role-row {
                grid-template-columns: minmax(0, 1fr) 38px;
                gap: 8px;
            }

            .pc-role-row input:first-child {
                grid-column: 1;
            }

            .pc-role-row input:nth-child(2) {
                grid-column: 1;
                grid-row: 2;
            }

            .pc-role-row .pc-remove {
                grid-column: 2;
                grid-row: 1;
            }

            .pc-section-heading {
                align-items: flex-start;
            }

            .pc-add {
                min-width: 125px;
                font-size: 13px;
            }

            .pc-actions {
                display: grid;
                grid-template-columns: 1fr;
            }

            .pc-button {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
@include('v_layouts.navbar')

@php
    $formRoles = array_values(
        old('roles', [['name' => '', 'criteria' => '']]) ?? []
    );

    $formMilestones = array_values(
        old('milestones', ['']) ?? []
    );
@endphp

<main class="pc-page">
    <div class="pc-container">

        <header class="pc-heading">
            <h1>Upload Projek Baru</h1>
            <p>Tentukan detail projek</p>
        </header>

        @if($errors->any())
            <div class="pc-errors" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('projects.store') }}"
            enctype="multipart/form-data"
            class="pc-form"
        >
            @csrf

            <div class="pc-grid">
                <label class="pc-field">
                    <span>Nama / Judul Projek</span>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Contoh: Sistem Informasi Perpustakaan"
                        maxlength="255"
                        required
                    >
                </label>

                <label class="pc-field">
                    <span>Tenggat Waktu (Deadline)</span>

                    <input
                        type="date"
                        name="deadline"
                        value="{{ old('deadline') }}"
                    >
                </label>
            </div>

            <label class="pc-field pc-description">
                <span>Detail Projek</span>

                <textarea
                    name="description"
                    rows="2"
                    maxlength="5000"
                    placeholder="Jelaskan projek dan kebutuhan kolaborasimu"
                    required
                >{{ old('description') }}</textarea>
            </label>

            {{-- PERAN --}}
            <section class="pc-section" aria-labelledby="rolesHeading">
                <div class="pc-section-heading">
                    <h2 id="rolesHeading">
                        Role yang Dibutuhkan &amp; Kriterianya
                    </h2>

                    <button
                        type="button"
                        class="pc-add"
                        data-pc-add="roles"
                    >
                        + Tambah Role
                    </button>
                </div>

                <div
                    class="pc-list"
                    id="pcRoles"
                    data-next-index="{{ count($formRoles) }}"
                >
                    @foreach($formRoles as $index => $role)
                        <div class="pc-row pc-role-row">
                            <input
                                type="text"
                                name="roles[{{ $index }}][name]"
                                value="{{ $role['name'] ?? '' }}"
                                placeholder="Contoh: Frontend Developer"
                                aria-label="Nama peran"
                                maxlength="100"
                            >

                            <input
                                type="text"
                                name="roles[{{ $index }}][criteria]"
                                value="{{ $role['criteria'] ?? '' }}"
                                placeholder="Kriteria keahlian yang dibutuhkan"
                                aria-label="Kriteria peran"
                                maxlength="500"
                            >

                            <button
                                type="button"
                                class="pc-remove"
                                data-pc-remove
                                aria-label="Hapus peran"
                            >
                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- CAPAIAN --}}
            <section class="pc-section" aria-labelledby="milestonesHeading">
                <div class="pc-section-heading">
                    <h2 id="milestonesHeading">Rencana Capaian Projek</h2>

                    <button
                        type="button"
                        class="pc-add"
                        data-pc-add="milestones"
                    >
                        + Tambah Capaian
                    </button>
                </div>

                <div
                    class="pc-list"
                    id="pcMilestones"
                    data-next-index="{{ count($formMilestones) }}"
                >
                    @foreach($formMilestones as $index => $milestone)
                        <div class="pc-row">
                            <input
                                type="text"
                                name="milestones[{{ $index }}]"
                                value="{{ $milestone }}"
                                placeholder="Contoh: Menyelesaikan halaman login"
                                aria-label="Rencana capaian"
                                maxlength="255"
                            >

                            <button
                                type="button"
                                class="pc-remove"
                                data-pc-remove
                                aria-label="Hapus capaian"
                            >
                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </section>

{{-- FILE & DOKUMEN --}}
<section
    class="pc-section"
    aria-labelledby="documentsHeading"
>

    <div class="pc-section-heading">

        <h2 id="documentsHeading">
            File & Dokumen
        </h2>

        <button
            type="button"
            class="pc-add"
            id="addDocumentButton"
        >
            + Tambah File
        </button>

    </div>


    <div
        class="pc-list"
        id="documentsList"
    >

        <div class="pc-row document-row">

            <input
                type="file"
                name="documents[]"
                class="document-file-input"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.png,.jpg,.jpeg,.webp"
            >

            <button
                type="button"
                class="pc-remove"
                data-remove-document
                aria-label="Hapus file"
            >
                <i
                    class="fa-regular fa-trash-can"
                    aria-hidden="true"
                ></i>
            </button>

        </div>

    </div>


    <p class="pc-hint">
        File yang didukung: PDF, Word, Excel,
        PowerPoint, gambar, CSV, dan TXT.
        Maksimal 10 MB per file.
    </p>

</section>

{{-- TEMPLATE BARIS TAMBAHAN --}}
<template id="pcRoleTemplate">
    <div class="pc-row pc-role-row">
        <input
            type="text"
            data-field="name"
            placeholder="Contoh: Frontend Developer"
            aria-label="Nama peran"
            maxlength="100"
        >

        <input
            type="text"
            data-field="criteria"
            placeholder="Kriteria keahlian yang dibutuhkan"
            aria-label="Kriteria peran"
            maxlength="500"
        >

        <button
            type="button"
            class="pc-remove"
            data-pc-remove
            aria-label="Hapus peran"
        >
            <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
        </button>
    </div>
</template>

<template id="pcMilestoneTemplate">
    <div class="pc-row">
        <input
            type="text"
            placeholder="Contoh: Menyelesaikan halaman login"
            aria-label="Rencana capaian"
            maxlength="255"
        >

        <button
            type="button"
            class="pc-remove"
            data-pc-remove
            aria-label="Hapus capaian"
        >
            <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
        </button>
    </div>
</template>
{{-- ACTION --}}
<div class="pc-actions">

    <a
        href="{{ route('projects') }}"
        class="pc-button"
    >
        Batal
    </a>

    <button
        type="submit"
        class="pc-button pc-submit"
    >
        Buat Projek
    </button>

</div>

</form>

</div>
</main>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const lists = {
        roles: document.getElementById('pcRoles'),
        milestones: document.getElementById('pcMilestones'),
    };
    const templates = {
        roles: document.getElementById('pcRoleTemplate'),
        milestones: document.getElementById('pcMilestoneTemplate'),
    };

    document.querySelectorAll('[data-pc-add]').forEach(function (button) {
        button.addEventListener('click', function () {
            const type = button.dataset.pcAdd;
            const list = lists[type];
            const template = templates[type];
            if (!list || !template) return;

            const index = Number(list.dataset.nextIndex || 0);
            const row = template.content.firstElementChild.cloneNode(true);

            if (type === 'roles') {
                row.querySelector('[data-field="name"]').name = `roles[${index}][name]`;
                row.querySelector('[data-field="criteria"]').name = `roles[${index}][criteria]`;
            } else {
                row.querySelector('input').name = `milestones[${index}]`;
            }

            list.appendChild(row);
            list.dataset.nextIndex = index + 1;
            row.querySelector('input').focus();
        });
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-pc-remove]');
        if (button) button.closest('.pc-row')?.remove();
    });

    const documentsList =
        document.getElementById('documentsList');

    const addDocumentButton =
        document.getElementById('addDocumentButton');


    /*
    |--------------------------------------------------------------------------
    | TAMBAH FILE
    |--------------------------------------------------------------------------
    */

    if (addDocumentButton && documentsList) {

        addDocumentButton.addEventListener(
            'click',
            function () {

                const row =
                    document.createElement('div');

                row.className =
                    'pc-row document-row';


                row.innerHTML = `

                        <input
                            type="file"
                            name="documents[]"
                            class="document-file-input"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.png,.jpg,.jpeg,.webp"
                        >

                    <button
                        type="button"
                        class="pc-remove"
                        data-remove-document
                    >
                        🗑
                    </button>

                `;


                documentsList.appendChild(
                    row
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS BARIS FILE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '[data-remove-document]'
                );


            if (!button) {
                return;
            }


            const row =
                button.closest(
                    '.document-row'
                );


            if (row) {
                row.remove();
            }

        }
    );

});
</script>

@endpush
