@php
    $selectedConversation = $selectedConversation ?? null;
    $templates = $templates ?? collect();
    $historyUrl = $historyUrl ?? null;
    $historyLimit = $historyLimit ?? 30;
@endphp

<div class="wa-thread-card card" data-wa-thread-card>
    @include('admin.whatsapp.partials.ChatHeaderCompact', [
        'selectedConversation' => $selectedConversation,
        'statuses' => $statuses,
        'assignees' => $assignees,
    ])

    <div data-wa-thread-content class="wa-thread-content">
        <div class="wa-message-vault" data-wa-message-vault>
            <div class="wa-message-list" id="waMessages" data-wa-message-list data-wa-message-scroll
                data-wa-history-url="{{ $historyUrl }}" data-wa-history-limit="{{ $historyLimit }}">
                <div class="wa-history-loader d-none" data-wa-history-loading>
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    <small>Loading older messages...</small>
                </div>

                @forelse ($selectedConversation->messages as $message)
                    @include('admin.whatsapp.partials.message-bubble', [
                        'message' => $message,
                        'incoming' => $message->sender_type === 'contact',
                        'status' => $message->status,
                    ])
                @empty
                    <div class="wa-thread-empty text-muted text-center" data-wa-empty-state>
                        <div>
                            <i class="bi bi-chat-square-text display-6 d-block mb-2"></i>
                            <div>No messages yet.</div>
                            <small>Start the conversation with a reply.</small>
                        </div>
                        <div class="mt-4 w-100">
                            @include('admin.whatsapp.partials.loading-skeleton')
                        </div>
                    </div>
                @endforelse
            </div>

            <button type="button" class="btn btn-success btn-sm wa-scroll-newest" data-wa-scroll-newest>
                <i class="bi bi-arrow-down-circle me-1"></i>Newest
            </button>
        </div>

        <div class="wa-thread-composer" id="waComposer">
            <form method="POST"
                action="{{ orgRoute('admin.whatsapp.messages.send', ['conversation' => $selectedConversation->id]) }}"
                data-wa-send-form>
                @csrf
                <input type="hidden" name="type" value="text" data-wa-message-type>
                <div class="row g-2 mb-2">
                    <div class="col-12 col-lg-3">
                        <button class="btn btn-outline-primary w-100" type="button" data-wa-open-template-modal>
                            <i class="bi bi-card-text me-1"></i>Send Template Message
                        </button>
                    </div>
                    <div class="col-12 col-lg-9">
                        <textarea name="body" id="waMessageBody" class="form-control" rows="2" placeholder="Type your reply..."
                            data-wa-message-body></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <small class="text-muted">Enter to send text. Use "Send Template Message" for business-initiated
                        outreach.</small>
                    <div class="d-flex gap-2 align-items-center">
                        <button class="btn btn-outline-secondary btn-sm" type="button" disabled
                            title="Attachment sending is a prepared placeholder."><i
                                class="bi bi-paperclip"></i></button>
                        <button class="btn btn-success" type="submit" data-wa-send-button>
                            <i class="bi bi-send me-1"></i>Send
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card d-none" data-wa-empty-chat-wrap>
        <div class="card-body p-2">
            @include('admin.whatsapp.partials.chat-empty-state', [
                'selectedConversation' => $selectedConversation,
            ])
        </div>
    </div>
</div>
