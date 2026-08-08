@extends('layouts.usermain')
@section('clientmessage')
    <div class="container-fluid inbox-container">
        <!-- Message List -->
        <div class="message-list">
            {{-- @if ($contactMessages->isEmpty())
                <div class="message-list-item">
                    <p>No messages yet</p>
                </div>
            @else --}}
            <div class="message-list-item {{ $contactMessage->replies->isEmpty() ? 'bg-light' : '' }}"
                data-id="{{ $contactMessage->id }}" data-name="{{ $contactMessage->first_name }}"
                data-last_name="{{ $contactMessage->last_name }}" data-message="{{ $contactMessage->message }}"
                data-subject="{{ $contactMessage->subject }}"
                data-createdat="{{ $contactMessage->created_at->format('M. d Y h:ia') }}"
                data-reply="{{ $contactMessage->replies->isEmpty() ? null : $contactMessage->replies->last()->reply_message }}"
                data-reply-id="{{ $contactMessage->replies->isEmpty() ? null : $contactMessage->replies->last()->id }}">

                <h6>{{ $contactMessage->first_name }} {{ $contactMessage->last_name }}
                    <span class="badge bg-info">{{ ucfirst($contactMessage->message_type) }}</span>
                    <br>
                    @if ($contactMessage->replies->isEmpty())
                        <small class="text-danger">Not replied yet</small>
                    @elseif (!$contactMessage->replies->last()->read_status)
                        <span class="badge bg-warning new-badge">New</span>
                    @endif
                </h6>

                <p>{{ Str::limit($contactMessage->subject, 50) }}</p>
                <span class="message-timestamp">{{ $contactMessage->created_at->format('M. d Y h:ia') }}</span>
            </div>
                @foreach ($contactMessages as $contactMessage)
                    <div class="message-list-item {{ $contactMessage->replies->isEmpty() ? 'bg-light' : '' }}"
                        data-id="{{ $contactMessage->id }}" data-name="{{ $contactMessage->first_name }}"
                        data-last_name="{{ $contactMessage->last_name }}" data-message="{{ $contactMessage->message }}"
                        data-subject="{{ $contactMessage->subject }}"
                        data-createdat="{{ $contactMessage->created_at->format('M. d Y h:ia') }}"
                        data-reply="{{ $contactMessage->replies->isEmpty() ? null : $contactMessage->replies->last()->reply_message }}"
                        data-reply-id="{{ $contactMessage->replies->isEmpty() ? null : $contactMessage->replies->last()->id }}">

                        <h6>{{ $contactMessage->first_name }} {{ $contactMessage->last_name }}
                            <span class="badge bg-info">{{ ucfirst($contactMessage->message_type) }}</span>
                            <br>
                            @if ($contactMessage->replies->isEmpty())
                                <small class="text-danger">Not replied yet</small>
                            @elseif (!$contactMessage->replies->last()->read_status)
                                <span class="badge bg-warning new-badge">New</span>
                            @endif
                        </h6>

                        <p>{{ Str::limit($contactMessage->subject, 50) }}</p>
                        <span class="message-timestamp">{{ $contactMessage->created_at->format('M. d Y h:ia') }}</span>
                    </div>
                @endforeach
            {{-- @endif --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $contactMessages->links('pagination::bootstrap-4') }}
            </div>
        </div>

        <!-- Message Details -->
        <div class="message-details" id="message-details">
            <div class="message-header">
                <h5 id="message-author">Select a message to view details</h5>
                <span class="message-timestamp" id="message-timestamp"></span>
            </div>

            <div class="message-subject" style="margin-bottom: 2px" id="message-subject">
                <strong>Subject:</strong> <span>No subject</span>
            </div>

            <div class="message-body" id="message-body">
                <p>Message details will appear here.</p>
            </div>
            <hr>
            <!-- Replies Section -->
            <div id="replies-section" style="margin-top: 15px;">
                <h5>Replies</h5>
                <div id="reply-content">
                    <p>No replies yet</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const messageListItems = document.querySelectorAll(".message-list-item");
            const messageAuthor = document.getElementById("message-author");
            const messageSubject = document.getElementById("message-subject");
            const messageBody = document.getElementById("message-body");
            const messageTimestamp = document.getElementById("message-timestamp");
            const replyContent = document.getElementById("reply-content");

            function displayMessage(item) {
                // Clear previous replies
                replyContent.innerHTML = "";

                // Remove active class from all items
                messageListItems.forEach((i) => i.classList.remove("active"));

                // Add active class to the clicked item
                item.classList.add("active");

                // Get data attributes from the selected message
                const name = item.getAttribute("data-name");
                const lastName = item.getAttribute("data-last_name");
                const subject = item.getAttribute("data-subject");
                const message = item.getAttribute("data-message");
                const createdAt = item.getAttribute("data-createdat");
                const replyMessage = item.getAttribute("data-reply");
                const replyId = item.getAttribute("data-reply-id");

                // Update the message details section with selected message
                messageAuthor.textContent = `${name} ${lastName}`;
                messageSubject.innerHTML = `<strong>Subject:</strong> ${subject}`;
                messageBody.innerHTML = message.replace(/\n/g, "<br>");
                messageTimestamp.textContent = createdAt;

                // Display the selected reply or indicate no replies yet
                if (replyMessage) {
                    const replyDiv = document.createElement("div");
                    replyDiv.classList.add("reply-item");
                    replyDiv.innerHTML = `<strong>Admin:</strong> ${replyMessage} <br>
                        <span class="reply-timestamp">${createdAt}</span>`;
                    replyContent.appendChild(replyDiv);
                } else {
                    replyContent.innerHTML = "<p>No replies yet</p>";
                }

                // Mark the reply as read in the database via AJAX
                if (replyId) {
                    fetch(`/contactmessage/markAsReadOnClick/${replyId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              // Remove the "New" badge
                              const newBadge = item.querySelector(".new-badge");
                              if (newBadge) newBadge.remove();
                          }
                      });
                }
            }

            // Set the first message as default if available
            if (messageListItems.length > 0) {
                displayMessage(messageListItems[0]);
            }

            // Add click event to all message items
            messageListItems.forEach((item) => {
                item.addEventListener("click", function() {
                    displayMessage(item);
                });
            });
        });
    </script>
@endsection
