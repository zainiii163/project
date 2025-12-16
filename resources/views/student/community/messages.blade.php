@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Messages</h2>
        <p style="color: var(--text-secondary); margin-top: 5px;">Conversation with {{ $user->name }}</p>
    </div>
    <div class="adomx-page-actions">
        <a href="javascript:history.back()" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Messages</h3>
            </div>
            <div class="adomx-card-body" style="max-height: 600px; overflow-y: auto; padding: 20px;">
                <div id="messages-container">
                    @if(isset($messages) && count($messages) > 0)
                        @foreach($messages as $message)
                            <div style="margin-bottom: 20px; display: flex; {{ $message->from_id == auth()->id() ? 'justify-content: flex-end' : 'justify-content: flex-start' }};">
                                <div style="max-width: 70%; padding: 12px 16px; border-radius: 12px; background: {{ $message->from_id == auth()->id() ? 'var(--primary-color)' : 'var(--card-bg)' }}; color: {{ $message->from_id == auth()->id() ? 'white' : 'var(--text-color)' }};">
                                    <div style="margin-bottom: 5px;">
                                        <strong>{{ $message->from_id == auth()->id() ? 'You' : $user->name }}</strong>
                                    </div>
                                    <div>{{ $message->content }}</div>
                                    <div style="margin-top: 5px; font-size: 11px; opacity: 0.8;">
                                        {{ $message->created_at->format('M d, Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="adomx-table-empty" style="text-align: center; padding: 40px;">
                            <i class="fas fa-comments" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="adomx-card" style="margin-top: 20px;">
    <div class="adomx-card-body">
        <form action="{{ route('student.community.send-message', $user) }}" method="POST">
            @csrf
            <div style="display: flex; gap: 10px;">
                <textarea 
                    name="message" 
                    class="adomx-input" 
                    rows="3" 
                    placeholder="Type your message..." 
                    required
                    style="flex: 1;"
                ></textarea>
                <button type="submit" class="adomx-btn adomx-btn-primary" style="align-self: flex-end;">
                    <i class="fas fa-paper-plane"></i>
                    Send
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-scroll to bottom of messages
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
</script>
@endsection

