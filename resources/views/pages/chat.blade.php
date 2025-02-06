@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Chat</h5>
                    </div>
                    <div class="card-body">

                        <div class="chat-container" id="chat-container">
                        </div>

                        <div class="mt-3">
                            <form id="message-form">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Ketik pesan Anda..."
                                        name="message" id="message-input" required>
                                    <button class="btn btn-primary" type="submit">Kirim</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .chat-container {
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
        }

        .message {
            margin-bottom: 10px;
            clear: both;
            /* Important for proper float clearing */
        }

        .message-bubble {
            max-width: 70%;
            padding: 12px 20px;
            border-radius: 15px;
            font-size: 16px;
            line-height: 1.4;
            clear: both;
            /* Ensure each bubble clears floats */
        }

        .message-bubble.user {
            background-color: #e2f7cb;
            /* Light green for user */
            float: right;
            /* Float user bubbles to the right */
            text-align: right;
        }

        .message-bubble.bot {
            background-color: #e7e3e3;
            /* White for bot */
            float: left;
            /* Float bot bubbles to the left */
            text-align: left;
        }

        /* Optional: Add some margin to the left of the bot's bubbles */
        .message-bubble.bot {
            margin-left: 10px;
        }

        /* Optional: Add some margin to the right of the user's bubbles */
        .message-bubble.user {
            margin-right: 10px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#message-form").submit(function(event) {
                event.preventDefault();

                var message = $("#message-input").val();

                if (message.trim() !== "") {
                    appendMessage(message, 'user');
                    $("#message-input").val("");

                    setTimeout(function() {
                        var botReply = generateBotReply(message);
                        appendMessage(botReply, 'bot');

                        $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));
                    }, 500);

                    $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));

                }
            });
        });

        function appendMessage(message, sender) {
            $("#chat-container").append(`
                <div class="message">
                    <div class="message-bubble ${sender}">${message}</div>
                </div>
            `);
        }

        function generateBotReply(userMessage) {
            var responses = [
                "Tentu, ada yang bisa dibantu?",
                "Silakan ajukan pertanyaan Anda.",
                "Produk ini tersedia dalam berbagai warna.",
                "Terima kasih atas pesan Anda.",
                "Maaf, saya tidak mengerti pertanyaan Anda."
            ];
            var randomIndex = Math.floor(Math.random() * responses.length);
            return responses[randomIndex];
        }

        $(document).ready(function() {
            $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));
        });
    </script>
@endsection
