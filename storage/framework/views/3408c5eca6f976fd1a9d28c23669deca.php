<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
body { background-color: #f3f4f6; }
.support-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.page-header { margin-bottom: 30px; }
.page-header h1 { font-size: 32px; color: #1a202c; margin: 0 0 10px 0; }
.page-header p { color: #718096; font-size: 16px; margin: 0; }
.support-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px; }
.card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.card h2 { font-size: 20px; color: #1a202c; margin: 0 0 20px 0; font-weight: 600; }
.tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; }
.tab { padding: 12px 24px; background: none; border: none; color: #64748b; font-size: 16px; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.2s; }
.tab.active { color: #3b82f6; border-bottom-color: #3b82f6; font-weight: 600; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-size: 14px; color: #4a5568; margin-bottom: 5px; font-weight: 600; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; font-family: inherit; }
.form-group textarea { resize: vertical; min-height: 100px; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; }
.btn-primary { background: #3b82f6; color: white; }
.btn-success { background: #10b981; color: white; }
.btn-danger { background: #ef4444; color: white; }
.tickets-list { margin-top: 20px; }
.ticket-item { background: #f7fafc; border-left: 4px solid #3b82f6; padding: 15px; margin-bottom: 10px; border-radius: 4px; cursor: pointer; transition: all 0.2s; }
.ticket-item:hover { background: #edf2f7; }
.ticket-item.high { border-left-color: #ef4444; }
.ticket-item.medium { border-left-color: #f59e0b; }
.ticket-item.low { border-left-color: #10b981; }
.ticket-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.ticket-id { font-weight: 600; color: #2d3748; }
.ticket-status { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.ticket-status.open { background: #dbeafe; color: #1e40af; }
.ticket-status.closed { background: #e5e7eb; color: #374151; }
.ticket-subject { color: #2d3748; font-size: 14px; margin-bottom: 3px; }
.ticket-meta { font-size: 12px; color: #9ca3af; }

/* Chatbot Styles */
.chatbot-container { position: relative; height: 600px; display: flex; flex-direction: column; }
.chat-messages { flex: 1; overflow-y: auto; padding: 15px; background: #f7fafc; border-radius: 6px; margin-bottom: 15px; }
.chat-message { margin-bottom: 15px; display: flex; gap: 10px; }
.chat-message.user { flex-direction: row-reverse; }
.chat-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; color: white; flex-shrink: 0; }
.chat-avatar.bot { background: #3b82f6; }
.chat-avatar.user { background: #10b981; }
.chat-bubble { max-width: 70%; padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; }
.chat-message.bot .chat-bubble { background: white; color: #2d3748; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.chat-message.user .chat-bubble { background: #3b82f6; color: white; }
.chat-timestamp { font-size: 11px; color: #9ca3af; margin-top: 4px; }
.chat-input-container { display: flex; gap: 10px; }
.chat-input { flex: 1; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
.chat-send-btn { padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
.chat-send-btn:disabled { background: #9ca3af; cursor: not-allowed; }

/* Quick Actions */
.quick-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 20px; }
.quick-action { padding: 15px; background: #f7fafc; border: 2px solid #e2e8f0; border-radius: 8px; text-align: center; cursor: pointer; transition: all 0.2s; }
.quick-action:hover { background: #edf2f7; border-color: #3b82f6; }
.quick-action-icon { font-size: 24px; margin-bottom: 5px; }
.quick-action-text { font-size: 13px; color: #4a5568; font-weight: 600; }

.alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; }
.alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
.alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
.empty-state { text-align: center; padding: 40px; color: #9ca3af; }
</style>

<div class="support-container">
    <div class="page-header">
        <h1>Customer Support</h1>
        <p>Get help with your account, transfers, and more</p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="support-grid">
        <!-- Left Column: Tickets -->
        <div class="card">
            <div class="tabs">
                <button class="tab active" onclick="switchTab('tickets')">My Tickets</button>
                <button class="tab" onclick="switchTab('create')">Create Ticket</button>
            </div>

            <!-- My Tickets Tab -->
            <div id="tickets-tab" class="tab-content active">
                <div class="tickets-list">
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="ticket-item <?php echo e($ticket['priority']); ?>" onclick="window.location.href='<?php echo e(route('support.ticket', $ticket['id'])); ?>'">
                            <div class="ticket-header">
                                <span class="ticket-id"><?php echo e($ticket['id']); ?></span>
                                <span class="ticket-status <?php echo e($ticket['status']); ?>"><?php echo e(ucfirst($ticket['status'])); ?></span>
                            </div>
                            <div class="ticket-subject"><?php echo e($ticket['subject']); ?></div>
                            <div class="ticket-meta">
                                <?php echo e($ticket['category']); ?> • <?php echo e($ticket['priority']); ?> priority • 
                                <?php echo e(\Carbon\Carbon::parse($ticket['created_at'])->diffForHumans()); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state">
                            <p>📋 No support tickets yet</p>
                            <p style="font-size: 13px;">Create a ticket if you need help with anything</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Create Ticket Tab -->
            <div id="create-tab" class="tab-content">
                <form action="<?php echo e(route('support.create-ticket')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label>Subject *</label>
                        <input type="text" name="subject" required placeholder="Brief description of your issue">
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <select name="category" required>
                            <option value="">Select Category</option>
                            <option value="account">Account & Login</option>
                            <option value="transfer">Money Transfer</option>
                            <option value="bank_account">Bank Account</option>
                            <option value="verification">Verification</option>
                            <option value="payment">Payment Issues</option>
                            <option value="fees">Fees & Rates</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Priority *</label>
                        <select name="priority" required>
                            <option value="low">Low - General inquiry</option>
                            <option value="medium" selected>Medium - Need help</option>
                            <option value="high">High - Urgent issue</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="description" required placeholder="Please describe your issue in detail..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Ticket</button>
                </form>
            </div>
        </div>

        <!-- Right Column: Chatbot -->
        <div class="card">
            <h2>💬 Chat Support</h2>
            <div class="quick-actions">
                <div class="quick-action" onclick="sendQuickMessage('How do I send money?')">
                    <div class="quick-action-icon">💸</div>
                    <div class="quick-action-text">Send Money</div>
                </div>
                <div class="quick-action" onclick="sendQuickMessage('What are the transfer fees?')">
                    <div class="quick-action-icon">💰</div>
                    <div class="quick-action-text">Transfer Fees</div>
                </div>
                <div class="quick-action" onclick="sendQuickMessage('How to verify my account?')">
                    <div class="quick-action-icon">✅</div>
                    <div class="quick-action-text">Verification</div>
                </div>
                <div class="quick-action" onclick="sendQuickMessage('How to add a bank account?')">
                    <div class="quick-action-icon">🏦</div>
                    <div class="quick-action-text">Bank Account</div>
                </div>
            </div>

            <div class="chatbot-container">
                <div class="chat-messages" id="chat-messages">
                    <div class="chat-message bot">
                        <div class="chat-avatar bot">🤖</div>
                        <div>
                            <div class="chat-bubble">
                                Hello! 👋 I'm your SwiftPay assistant. How can I help you today?
                            </div>
                            <div class="chat-timestamp">Just now</div>
                        </div>
                    </div>
                </div>
                <div class="chat-input-container">
                    <input type="text" id="chat-input" class="chat-input" placeholder="Type your message..." onkeypress="if(event.key === 'Enter') sendMessage()">
                    <button class="chat-send-btn" onclick="sendMessage()">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.querySelectorAll('.tab').forEach(button => {
        button.classList.remove('active');
    });
    
    document.getElementById(tab + '-tab').classList.add('active');
    event.target.classList.add('active');
}

function sendQuickMessage(message) {
    document.getElementById('chat-input').value = message;
    sendMessage();
}

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message to chat
    addMessageToChat('user', message);
    input.value = '';
    
    // Disable input while waiting
    input.disabled = true;
    
    try {
        const response = await fetch('<?php echo e(route('support.chatbot')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ message: message })
        });
        
        const data = await response.json();
        
        // Add bot response
        addMessageToChat('bot', data.message);
    } catch (error) {
        addMessageToChat('bot', 'Sorry, I encountered an error. Please try again or create a support ticket.');
    }
    
    input.disabled = false;
    input.focus();
}

function addMessageToChat(sender, message) {
    const messagesContainer = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${sender}`;
    
    const avatar = sender === 'bot' ? '🤖' : '👤';
    const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    
    messageDiv.innerHTML = `
        <div class="chat-avatar ${sender}">${avatar}</div>
        <div>
            <div class="chat-bubble">${escapeHtml(message).replace(/\n/g, '<br>')}</div>
            <div class="chat-timestamp">${timestamp}</div>
        </div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

</main></body></html>
<?php /**PATH C:\xampp\htdocs\money-transfer\resources\views/support/index.blade.php ENDPATH**/ ?>