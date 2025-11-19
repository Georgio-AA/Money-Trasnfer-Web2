@include('includes.header')

<style>
body { background-color: #f3f4f6; }
.support-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
.back-link { display: inline-block; color: #3b82f6; text-decoration: none; margin-bottom: 20px; font-size: 14px; }
.back-link:hover { text-decoration: underline; }
.ticket-card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.ticket-header { border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px; }
.ticket-title { font-size: 24px; color: #1a202c; margin: 0 0 10px 0; }
.ticket-meta { display: flex; gap: 15px; flex-wrap: wrap; font-size: 13px; color: #718096; }
.ticket-meta-item { display: flex; align-items: center; gap: 5px; }
.ticket-status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.ticket-status-badge.open { background: #dbeafe; color: #1e40af; }
.ticket-status-badge.closed { background: #e5e7eb; color: #374151; }
.priority-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.priority-badge.high { background: #fee2e2; color: #991b1b; }
.priority-badge.medium { background: #fef3c7; color: #92400e; }
.priority-badge.low { background: #d1fae5; color: #065f46; }
.ticket-description { background: #f7fafc; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
.ticket-description h3 { font-size: 16px; color: #2d3748; margin: 0 0 10px 0; }
.ticket-description p { color: #4a5568; line-height: 1.6; margin: 0; }
.messages-section { margin-top: 30px; }
.messages-section h3 { font-size: 18px; color: #2d3748; margin: 0 0 15px 0; }
.message { background: #f7fafc; padding: 15px; border-radius: 6px; margin-bottom: 15px; }
.message.admin { background: #dbeafe; }
.message-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.message-sender { font-weight: 600; color: #2d3748; font-size: 14px; }
.message-time { font-size: 12px; color: #9ca3af; }
.message-body { color: #4a5568; line-height: 1.6; font-size: 14px; }
.reply-form { margin-top: 20px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-size: 14px; color: #4a5568; margin-bottom: 5px; font-weight: 600; }
.form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; font-family: inherit; resize: vertical; min-height: 100px; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; }
.btn-primary { background: #3b82f6; color: white; }
.btn-danger { background: #ef4444; color: white; margin-left: 10px; }
.alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; }
.alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
.alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
.empty-messages { text-align: center; padding: 40px; color: #9ca3af; }
</style>

<div class="support-container">
    <a href="{{ route('support.index') }}" class="back-link">← Back to Support</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="ticket-card">
        <div class="ticket-header">
            <h1 class="ticket-title">{{ $ticket['subject'] }}</h1>
            <div class="ticket-meta">
                <div class="ticket-meta-item">
                    <strong>Ticket ID:</strong> {{ $ticket['id'] }}
                </div>
                <div class="ticket-meta-item">
                    <strong>Status:</strong>
                    <span class="ticket-status-badge {{ $ticket['status'] }}">{{ ucfirst($ticket['status']) }}</span>
                </div>
                <div class="ticket-meta-item">
                    <strong>Priority:</strong>
                    <span class="priority-badge {{ $ticket['priority'] }}">{{ ucfirst($ticket['priority']) }}</span>
                </div>
                <div class="ticket-meta-item">
                    <strong>Category:</strong> {{ ucfirst(str_replace('_', ' ', $ticket['category'])) }}
                </div>
                <div class="ticket-meta-item">
                    <strong>Created:</strong> {{ \Carbon\Carbon::parse($ticket['created_at'])->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>

        <div class="ticket-description">
            <h3>Description</h3>
            <p>{{ $ticket['description'] }}</p>
        </div>

        <div class="messages-section">
            <h3>Messages ({{ count($ticket['messages']) }})</h3>
            
            @forelse($ticket['messages'] as $message)
                <div class="message {{ $message['from'] === 'admin' ? 'admin' : '' }}">
                    <div class="message-header">
                        <span class="message-sender">
                            {{ $message['from'] === 'admin' ? '👨‍💼 Support Team' : '👤 ' . $message['name'] }}
                        </span>
                        <span class="message-time">{{ \Carbon\Carbon::parse($message['timestamp'])->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="message-body">{{ $message['message'] }}</div>
                </div>
            @empty
                <div class="empty-messages">
                    <p>💬 No messages yet</p>
                    <p style="font-size: 13px;">Send a message to continue the conversation</p>
                </div>
            @endforelse
        </div>

        @if($ticket['status'] !== 'closed')
            <div class="reply-form">
                <form action="{{ route('support.add-message', $ticket['id']) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Add Message</label>
                        <textarea name="message" required placeholder="Type your message here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>

                <form action="{{ route('support.close-ticket', $ticket['id']) }}" method="POST" style="display: inline; margin-top: 15px;" onsubmit="return confirm('Are you sure you want to close this ticket?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">Close Ticket</button>
                </form>
            </div>
        @else
            <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 6px; color: #718096;">
                <p>This ticket is closed. To continue, please create a new ticket.</p>
            </div>
        @endif
    </div>
</div>

</main></body></html>
