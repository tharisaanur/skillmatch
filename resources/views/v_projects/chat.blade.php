@extends('v_layouts.app')
@section('title', $project->title . ' - Chat')
@push('styles')
    @vite('resources/css/projects.css')
@endpush
@section('content')
@include('v_layouts.navbar')
<main class="chat-page">
    <section class="chat-shell">
        <header class="chat-header">
            <a href="{{ route('projects') }}" class="chat-back">← Kembali</a>
            <div><h1>{{ $project->title }}</h1><p><i></i> Chat Kelompok ({{ $memberCount }} Anggota)</p></div>
        </header>
        <div
    class="chat-messages"
    id="chatMessages"
>

    @foreach($messages as $message)

        <div
            class="chat-message
            {{ $message->user_id === auth()->id()
                ? 'chat-message-me'
                : 'chat-message-other' }}"
            data-message-id="{{ $message->id }}"
        >

            <strong>
                {{ $message->user->name }}
            </strong>

            <p>
                {{ $message->message }}
            </p>

            <small>
                {{ $message->created_at
                    ->timezone('Asia/Jakarta')
                    ->format('H:i') }}
            </small>

        </div>

    @endforeach

</div>
        <form
    action="{{ route('projects.chat.store', $project) }}"
    method="POST"
    id="chatForm"
    class="chat-form"
>

    @csrf

    <textarea
        name="message"
        id="chatInput"
        placeholder="Ketik Pesan disini..."
        required
    ></textarea>

    <button
        type="submit"
        id="chatSendButton"
    >
        Kirim
    </button>

</form>
    </section>
</main>
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const chatMessages =
        document.getElementById('chatMessages');

    const chatForm =
        document.getElementById('chatForm');

    const chatInput =
        document.getElementById('chatInput');

    const sendButton =
        document.getElementById('chatSendButton');


    const messageUrl =
        @json(
            route(
                'projects.chat.messages',
                $project
            )
        );

    const sendUrl =
        @json(
            route(
                'projects.chat.store',
                $project
            )
        );


    /*
    |--------------------------------------------------------------------------
    | MESSAGE TERAKHIR
    |--------------------------------------------------------------------------
    */

    let lastMessageId = 0;


    const existingMessages =
        chatMessages.querySelectorAll(
            '[data-message-id]'
        );


    if (existingMessages.length > 0) {

        lastMessageId =
            parseInt(
                existingMessages[
                    existingMessages.length - 1
                ].dataset.messageId
            ) || 0;

    }


    /*
    |--------------------------------------------------------------------------
    | SCROLL KE BAWAH
    |--------------------------------------------------------------------------
    */

    function scrollToBottom() {

        chatMessages.scrollTop =
            chatMessages.scrollHeight;

    }


    scrollToBottom();


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(text) {

        const div =
            document.createElement('div');

        div.textContent =
            text;

        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN PESAN
    |--------------------------------------------------------------------------
    */

    function appendMessage(message) {

        /*
        | Hindari pesan duplikat
        */

        if (
            document.querySelector(
                `[data-message-id="${message.id}"]`
            )
        ) {
            return;
        }


        const element =
            document.createElement('div');


        element.dataset.messageId =
            message.id;


        element.className =
            'chat-message ' +
            (
                message.is_me
                    ? 'chat-message-me'
                    : 'chat-message-other'
            );


        element.innerHTML = `

            <strong>
                ${escapeHtml(message.user_name)}
            </strong>

            <p>
                ${escapeHtml(message.message)}
            </p>

            <small>
                ${escapeHtml(message.time)}
            </small>

        `;


        chatMessages.appendChild(
            element
        );


        lastMessageId =
            Math.max(
                lastMessageId,
                Number(message.id)
            );


        scrollToBottom();

    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL PESAN BARU
    |--------------------------------------------------------------------------
    */

    async function loadNewMessages() {

        try {

            const response =
                await fetch(
                    `${messageUrl}?last_id=${lastMessageId}`,
                    {
                        headers: {
                            'Accept':
                                'application/json'
                        }
                    }
                );


            if (!response.ok) {
                return;
            }


            const data =
                await response.json();


            data.messages.forEach(
                function (message) {

                    appendMessage(
                        message
                    );

                }
            );

        } catch (error) {

            console.error(
                'Gagal mengambil pesan:',
                error
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | KIRIM TANPA REFRESH
    |--------------------------------------------------------------------------
    */

chatForm.addEventListener(
    'submit',
    async function (event) {

        event.preventDefault();


        const message =
            chatInput.value.trim();


        if (!message) {
            return;
        }


        sendButton.disabled = true;


        try {

            /*
            |--------------------------------------------------------------------------
            | AMBIL DATA FORM
            |--------------------------------------------------------------------------
            |
            | Termasuk:
            | - message
            | - _token dari @csrf
            |
            */

            const formData =
                new FormData(chatForm);


            const response =
                await fetch(
                    sendUrl,
                    {
                        method: 'POST',

                        headers: {
                            'Accept': 'application/json'
                        },

                        body: formData
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | CEK RESPONSE ERROR
            |--------------------------------------------------------------------------
            */

            if (!response.ok) {

                const errorText =
                    await response.text();

                console.error(
                    'Server error:',
                    response.status,
                    errorText
                );

                throw new Error(
                    'Pesan gagal dikirim'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | RESPONSE JSON
            |--------------------------------------------------------------------------
            */

            const data =
                await response.json();


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN PESAN
            |--------------------------------------------------------------------------
            */

            appendMessage(
                data.message
            );


            /*
            |--------------------------------------------------------------------------
            | KOSONGKAN INPUT
            |--------------------------------------------------------------------------
            */

            chatInput.value = '';

            chatInput.focus();


        } catch (error) {

            console.error(
                'Gagal mengirim pesan:',
                error
            );


            alert(
                'Pesan gagal dikirim. Coba lagi.'
            );

        } finally {

            sendButton.disabled =
                false;

        }

    }
);

    /*
    |--------------------------------------------------------------------------
    | ENTER = KIRIM
    | SHIFT + ENTER = BARIS BARU
    |--------------------------------------------------------------------------
    */

    chatInput.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Enter'
                &&
                !event.shiftKey
            ) {

                event.preventDefault();

                chatForm.requestSubmit();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REALTIME POLLING
    |--------------------------------------------------------------------------
    |
    | Setiap 1 detik cek apakah ada pesan baru.
    |
    */

    setInterval(
        loadNewMessages,
        1000
    );

});
</script>

@endpush
@endsection
