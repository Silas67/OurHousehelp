@extends('layouts.adminmain')
@section('contactmessage')
    <div class="container-fluid inbox-container">
        <!-- Message List -->
        <div class="message-list">
            @if ($contactMessages->isEmpty())
                <div class="message-list-item">
                    <p>No messages yet</p>
                </div>
            @else
                @foreach ($contactMessages as $contactMessage)
                    <div class="message-list-item p-3 mb-2 rounded cursor-pointer" data-id="{{ $contactMessage->id }}"
                        data-sender="{{ $contactMessage->first_name . ' ' . $contactMessage->last_name }}"
                        data-subject="{{ $contactMessage->subject }}" data-email="{{ $contactMessage->email }}"
                        data-created-at="{{ $contactMessage->created_at->format('M. d Y h:ia') }}"
                        data-messagetype="{{ ucfirst($contactMessage->message_type) }}"
                        data-replies="{{ $contactMessage->replies->toJson() }}">

                        <h6 class="mb-1">{{ $contactMessage->first_name }}
                            <span class="badge bg-info">{{ ucfirst($contactMessage->message_type) }}</span>
                            @if (!$contactMessage->read_status)
                                <span class="badge bg-warning">New</span>
                            @endif
                        </h6>
                        <p class="mb-0">{{ Str::limit($contactMessage->subject, 50) }}</p>
                        <small class="text-muted">{{ $contactMessage->created_at->format('M. d Y h:ia') }}</small>
                        <br>
                        @if ($contactMessage->replies->isEmpty())
                            <span class="text-danger" style="font-size: 12px">Not yet replied</span><br>
                        @endif

                        @if (!$contactMessage->read_status)
                            <form action="{{ route('contactmessage.markAsRead', $contactMessage->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">Mark as Read</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            @endif
            <div class="mt-3 d-flex justify-content-center">
                {{ $contactMessages->links('pagination::bootstrap-4') }}
            </div>
        </div>

        <!-- Main Content: Message and Reply Section -->
        <div class="col-md-8 message-details" id="message-details">
            <div class="message-header">
                <h5 id="message-author">Sender's name here</h5>
                <span class="message-timestamp" id="message-timestamp"></span>
            </div>

            <!-- Message Content -->
            <div id="message-content" class="p-4" style="display: none;">
                <h5 id="message-subject" class="mb-3"></h5>
                <p id="message-sender" class="text-muted mb-2"></p>
                <small id="message-sender-timestamp" class="text-muted mb-4"></small>
                <hr>

                <!-- Replies Section -->
                <div id="replies-section" class="mb-4">
                    <h5>Replies</h5>
                    <div id="replies-list"></div>
                </div>

                <!-- Reply Form -->
                {{-- <div id="reply-form-container">
                    <form id="reply-form" method="POST"
                        action="{{ route('contactmessages.reply', $contactMessage->id) }}">
                        @csrf
                        <div class="form-group mb-3">
                            <textarea class="form-control" name="reply_message" rows="4" placeholder="Write your reply..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Send Reply</button>
                        </div>
                    </form>
                </div> --}}
                <div id="reply-form-container">
                    <form id="reply-form" method="POST" action="{{ route('contactmessages.reply', 0) }}">
                        @csrf
                        <input type="hidden" name="message_id" id="message-id" value="">
                        <div class="form-group mb-3">
                            <textarea class="form-control" name="reply_message" rows="4" placeholder="Write your reply..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Send Reply</button>
                        </div>
                    </form>
                </div>


            </div>
        </div>

    </div>

    <!-- JavaScript for Loading Message Details and Replies -->
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const messageListItems = document.querySelectorAll(".message-list-item");
            const messageAuthor = document.getElementById("message-author");
            const messageTimestamp = document.getElementById("message-timestamp");
            const messageSubject = document.getElementById("message-subject");
            const messageSender = document.getElementById("message-sender");
            const messageContent = document.getElementById("message-content");
            const repliesList = document.getElementById("replies-list");
            const replyFormContainer = document.getElementById("reply-form-container");

            // Function to handle message selection and details display
            function loadMessageDetails(item) {
                const sender = item.getAttribute("data-sender");
                const subject = item.getAttribute("data-subject");
                const timestamp = item.getAttribute("data-created-at");
                const replies = JSON.parse(item.getAttribute("data-replies"));
                const messageId = item.getAttribute("data-id");

                // Set the message details in the content section
                messageAuthor.textContent = sender;
                messageTimestamp.textContent = "Received: " + timestamp;
                messageSubject.textContent = subject;
                messageSender.textContent = "From: " + sender;
                messageContent.style.display = "block"; // Show the message content

                // Populate the replies section
                repliesList.innerHTML = ""; // Clear previous replies
                if (replies.length) {
                    replies.forEach(reply => {
                        const replyItem = document.createElement("div");
                        replyItem.classList.add("reply-item", "border-bottom", "pb-2", "mb-2");
                        replyItem.innerHTML = `
                    <strong>Admin:</strong> ${reply.reply_message} <br>
                    <small class="text-muted">${new Date(reply.created_at).toLocaleString()}</small>
                `;
                        repliesList.appendChild(replyItem);
                    });
                } else {
                    repliesList.innerHTML = "<p class='text-muted'>No replies yet</p>";
                }

                // Show the reply form
                replyFormContainer.style.display = "block";

                // If message is marked as "New", send AJAX request to mark as read
                const newBadge = item.querySelector(".badge.bg-warning");
                if (newBadge) {
                    fetch(`/contactmessage/markAsRead/${messageId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the "New" badge from the UI
                                newBadge.remove();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            }

            // Add click event listener to each message item
            messageListItems.forEach(item => {
                item.addEventListener("click", function() {
                    loadMessageDetails(item);
                });
            });

            // Load the first message by default if messages are present
            if (messageListItems.length > 0) {
                loadMessageDetails(messageListItems[0]);
            }
        });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const messageListItems = document.querySelectorAll(".message-list-item");
            const messageAuthor = document.getElementById("message-author");
            const messageTimestamp = document.getElementById("message-timestamp");
            const messageSubject = document.getElementById("message-subject");
            const messageSender = document.getElementById("message-sender");
            const messageContent = document.getElementById("message-content");
            const repliesList = document.getElementById("replies-list");
            const replyFormContainer = document.getElementById("reply-form-container");
            const messageIdInput = document.getElementById("message-id"); // Hidden input for message ID in the form

            // Function to handle message selection and details display
            function loadMessageDetails(item) {
                const sender = item.getAttribute("data-sender");
                const subject = item.getAttribute("data-subject");
                const timestamp = item.getAttribute("data-created-at");
                const replies = JSON.parse(item.getAttribute("data-replies"));
                const messageId = item.getAttribute("data-id");

                // Set the message details in the content section
                messageAuthor.textContent = sender;
                messageTimestamp.textContent = "Received: " + timestamp;
                messageSubject.textContent = subject;
                messageSender.textContent = "From: " + sender;
                messageContent.style.display = "block"; // Show the message content

                // Populate the replies section
                repliesList.innerHTML = ""; // Clear previous replies
                if (replies.length) {
                    replies.forEach(reply => {
                        const replyItem = document.createElement("div");
                        replyItem.classList.add("reply-item", "border-bottom", "pb-2", "mb-2");
                        replyItem.innerHTML = `
                    <strong>Admin:</strong> ${reply.reply_message} <br>
                    <small class="text-muted">${new Date(reply.created_at).toLocaleString()}</small>
                `;
                        repliesList.appendChild(replyItem);
                    });
                } else {
                    repliesList.innerHTML = "<p class='text-muted'>No replies yet</p>";
                }

                // Set the message ID in the hidden input field of the reply form
                messageIdInput.value = messageId;

                // Show the reply form
                replyFormContainer.style.display = "block";

                // If message is marked as "New", send AJAX request to mark as read
                const newBadge = item.querySelector(".badge.bg-warning");
                if (newBadge) {
                    fetch(`/contactmessage/markAsRead/${messageId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the "New" badge from the UI
                                newBadge.remove();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            }

            // Add click event listener to each message item
            messageListItems.forEach(item => {
                item.addEventListener("click", function() {
                    loadMessageDetails(item);
                });
            });

            // Load the first message by default if messages are present
            if (messageListItems.length > 0) {
                loadMessageDetails(messageListItems[0]);
            }
        });
    </script>
@endsection
