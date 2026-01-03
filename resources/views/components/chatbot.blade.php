<!-- Chatbot Widget -->
<div id="chatbot-widget" class="chatbot-widget" style="display: none;">
    <div class="chatbot-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                <i class="fas fa-robot"></i>
            </div>
            <div>
                <div style="font-weight: 600; font-size: 16px;">Support Assistant</div>
                <div style="font-size: 12px; color: var(--text-secondary);">We're here to help!</div>
            </div>
        </div>
        <button onclick="toggleChatbot()" class="chatbot-close-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="chatbot-messages" id="chatbot-messages">
        <div class="chatbot-message chatbot-message-bot">
            <div class="chatbot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="chatbot-content">
                <div class="chatbot-text">Hello! 👋 I'm your support assistant. How can I help you today?</div>
                <div class="chatbot-time">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>
    
    <div class="chatbot-suggestions" id="chatbot-suggestions">
        <button class="chatbot-suggestion-btn" onclick="sendSuggestion('I need help with a course')">I need help with a course</button>
        <button class="chatbot-suggestion-btn" onclick="sendSuggestion('I have a billing question')">I have a billing question</button>
        <button class="chatbot-suggestion-btn" onclick="sendSuggestion('I forgot my password')">I forgot my password</button>
        <button class="chatbot-suggestion-btn" onclick="sendSuggestion('I want to report a technical issue')">Report technical issue</button>
    </div>
    
    <div class="chatbot-input-area">
        <form id="chatbot-form" onsubmit="sendChatMessage(event)">
            <input type="text" id="chatbot-input" class="chatbot-input" placeholder="Type your message..." autocomplete="off">
            <button type="submit" class="chatbot-send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<!-- Chatbot Toggle Button -->
<button id="chatbot-toggle" class="chatbot-toggle-btn" onclick="toggleChatbot()" title="Chat with Support">
    <i class="fas fa-comments"></i>
    <span class="chatbot-badge" id="chatbot-badge" style="display: none;">1</span>
</button>

<style>
.chatbot-toggle-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    transition: all 0.3s ease;
}

.chatbot-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.chatbot-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.chatbot-widget {
    position: fixed;
    bottom: 100px;
    right: 30px;
    width: 380px;
    height: 600px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    z-index: 1001;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chatbot-header {
    background: var(--primary-color);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-close-btn {
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 20px;
    padding: 5px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.2s;
}

.chatbot-close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.chatbot-message {
    display: flex;
    gap: 10px;
    animation: fadeIn 0.3s ease;
}

.chatbot-message-user {
    flex-direction: row-reverse;
}

.chatbot-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
}

.chatbot-message-user .chatbot-avatar {
    background: var(--success-color);
}

.chatbot-content {
    flex: 1;
    max-width: 75%;
}

.chatbot-text {
    background: white;
    padding: 10px 15px;
    border-radius: 12px;
    word-wrap: break-word;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chatbot-message-user .chatbot-text {
    background: var(--primary-color);
    color: white;
}

.chatbot-time {
    font-size: 11px;
    color: var(--text-secondary);
    margin-top: 5px;
    padding: 0 5px;
}

.chatbot-message-user .chatbot-time {
    text-align: right;
}

.chatbot-suggestions {
    padding: 15px;
    background: white;
    border-top: 1px solid var(--border-color);
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    max-height: 120px;
    overflow-y: auto;
}

.chatbot-suggestion-btn {
    padding: 8px 12px;
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.chatbot-suggestion-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.chatbot-input-area {
    padding: 15px;
    background: white;
    border-top: 1px solid var(--border-color);
}

#chatbot-form {
    display: flex;
    gap: 10px;
}

.chatbot-input {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid var(--border-color);
    border-radius: 25px;
    font-size: 14px;
    outline: none;
}

.chatbot-input:focus {
    border-color: var(--primary-color);
}

.chatbot-send-btn {
    width: 44px;
    height: 44px;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.chatbot-send-btn:hover {
    transform: scale(1.1);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .chatbot-widget {
        width: calc(100% - 20px);
        right: 10px;
        bottom: 80px;
        height: calc(100vh - 100px);
    }
    
    .chatbot-toggle-btn {
        bottom: 20px;
        right: 20px;
    }
}
</style>

<script>
let chatSessionId = null;
let isTyping = false;

function toggleChatbot() {
    const widget = document.getElementById('chatbot-widget');
    const toggle = document.getElementById('chatbot-toggle');
    
    if (widget.style.display === 'none') {
        widget.style.display = 'flex';
        toggle.style.display = 'none';
        document.getElementById('chatbot-input').focus();
    } else {
        widget.style.display = 'none';
        toggle.style.display = 'flex';
    }
}

function sendSuggestion(text) {
    document.getElementById('chatbot-input').value = text;
    sendChatMessage(new Event('submit'));
}

function sendChatMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('chatbot-input');
    const message = input.value.trim();
    
    if (!message || isTyping) return;
    
    // Add user message to chat
    addMessage(message, 'user');
    input.value = '';
    
    // Hide suggestions
    document.getElementById('chatbot-suggestions').style.display = 'none';
    
    // Show typing indicator
    isTyping = true;
    const typingId = addTypingIndicator();
    
    // Send to backend
    fetch('{{ route("chatbot.chat") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            message: message,
            session_id: chatSessionId
        })
    })
    .then(response => response.json())
    .then(data => {
        removeTypingIndicator(typingId);
        isTyping = false;
        
        if (data.success) {
            chatSessionId = data.session_id;
            addMessage(data.response, 'bot');
            
            // Show suggestions if available
            if (data.suggestions && data.suggestions.length > 0) {
                showSuggestions(data.suggestions);
            }
            
            // Show create ticket option if needed
            if (data.create_ticket) {
                showCreateTicketOption();
            }
        }
    })
    .catch(error => {
        removeTypingIndicator(typingId);
        isTyping = false;
        addMessage('Sorry, I encountered an error. Please try again or create a support ticket.', 'bot');
    });
}

function addMessage(text, type) {
    const messages = document.getElementById('chatbot-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `chatbot-message chatbot-message-${type}`;
    
    const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    
    messageDiv.innerHTML = `
        <div class="chatbot-avatar">
            <i class="fas fa-${type === 'bot' ? 'robot' : 'user'}"></i>
        </div>
        <div class="chatbot-content">
            <div class="chatbot-text">${escapeHtml(text)}</div>
            <div class="chatbot-time">${time}</div>
        </div>
    `;
    
    messages.appendChild(messageDiv);
    messages.scrollTop = messages.scrollHeight;
}

function addTypingIndicator() {
    const messages = document.getElementById('chatbot-messages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'chatbot-message chatbot-message-bot';
    typingDiv.id = 'typing-indicator';
    
    typingDiv.innerHTML = `
        <div class="chatbot-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="chatbot-content">
            <div class="chatbot-text">
                <span class="typing-dots">
                    <span>.</span><span>.</span><span>.</span>
                </span>
            </div>
        </div>
    `;
    
    messages.appendChild(typingDiv);
    messages.scrollTop = messages.scrollHeight;
    
    return 'typing-indicator';
}

function removeTypingIndicator(id) {
    const indicator = document.getElementById(id);
    if (indicator) {
        indicator.remove();
    }
}

function showSuggestions(suggestions) {
    const suggestionsDiv = document.getElementById('chatbot-suggestions');
    suggestionsDiv.innerHTML = '';
    suggestionsDiv.style.display = 'flex';
    
    suggestions.forEach(suggestion => {
        const btn = document.createElement('button');
        btn.className = 'chatbot-suggestion-btn';
        btn.textContent = suggestion;
        btn.onclick = () => sendSuggestion(suggestion);
        suggestionsDiv.appendChild(btn);
    });
}

function showCreateTicketOption() {
    const messages = document.getElementById('chatbot-messages');
    const ticketDiv = document.createElement('div');
    ticketDiv.className = 'chatbot-message chatbot-message-bot';
    
    ticketDiv.innerHTML = `
        <div class="chatbot-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="chatbot-content">
            <div class="chatbot-text">
                Would you like to create a support ticket for this issue? Our team will get back to you soon.
                <br><br>
                <a href="{{ route('support.create') }}" class="adomx-btn adomx-btn-sm adomx-btn-primary" style="margin-top: 10px; display: inline-block;">
                    <i class="fas fa-ticket-alt"></i> Create Support Ticket
                </a>
            </div>
        </div>
    `;
    
    messages.appendChild(ticketDiv);
    messages.scrollTop = messages.scrollHeight;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close chatbot when clicking outside (optional)
document.addEventListener('click', function(event) {
    const widget = document.getElementById('chatbot-widget');
    const toggle = document.getElementById('chatbot-toggle');
    
    if (widget && widget.style.display !== 'none' && 
        !widget.contains(event.target) && 
        !toggle.contains(event.target)) {
        // Keep it open for better UX, or uncomment to close:
        // toggleChatbot();
    }
});
</script>

