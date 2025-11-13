@include('includes.header')

<section class="page-header">
    <h1>Verify Bank Account</h1>
    <p>Complete the verification process for {{ $bankAccount->bank_name }}</p>
</section>

<section class="verification">
    <div class="container">
        <div class="account-info">
            <h3>Account Details</h3>
            <div class="account-card">
                <p><strong>Bank:</strong> {{ $bankAccount->bank_name }}</p>
                <p><strong>Account:</strong> ****{{ substr($bankAccount->account_number, -4) }}</p>
                <p><strong>Currency:</strong> {{ $bankAccount->currency }}</p>
                <p><strong>Type:</strong> {{ ucfirst($bankAccount->account_type) ?? 'Not specified' }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <div class="verification-methods">
            <h3>Choose Verification Method</h3>
            
            <div class="method-tabs">
                <button class="tab-btn active" onclick="showTab('document')">Document Upload</button>
                <button class="tab-btn" onclick="showTab('micro')">Micro-transfers</button>
            </div>

            <!-- Document Upload Method -->
            <div id="document-tab" class="tab-content active">
                <div class="method-card">
                    <div class="method-header">
                        <h4>📄 Document Upload</h4>
                        <p>Upload a bank statement or void check to verify your account instantly</p>
                    </div>

                    <form action="{{ route('bank-accounts.verify', $bankAccount) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="verification_method" value="document">

                        <div class="form-group">
                            <label for="document">Upload Document *</label>
                            <div class="file-upload-area" onclick="document.getElementById('document').click()">
                                <div class="upload-placeholder">
                                    <div class="upload-icon">📁</div>
                                    <p>Click to select file or drag and drop</p>
                                    <p class="file-info">Accepted: JPG, PNG, PDF (Max: 2MB)</p>
                                </div>
                                <input type="file" id="document" name="document" accept=".jpg,.jpeg,.png,.pdf" required style="display: none;" onchange="updateFileName(this)">
                            </div>
                            <div id="file-name" class="file-name"></div>
                            @error('document')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="requirements">
                            <h5>Document Requirements:</h5>
                            <ul>
                                <li>✓ Must clearly show your name and account number</li>
                                <li>✓ Document must be recent (within last 3 months)</li>
                                <li>✓ All information must be legible</li>
                                <li>✓ Bank letterhead or official stamp required</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">Upload & Verify</button>
                    </form>
                </div>
            </div>

            <!-- Micro-transfer Method -->
            <div id="micro-tab" class="tab-content">
                <div class="method-card">
                    <div class="method-header">
                        <h4>💰 Micro-transfers</h4>
                        <p>We'll send small amounts to your account for verification</p>
                    </div>

                    <div class="micro-steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h5>Request Micro-transfers</h5>
                                <p>We'll send 2 small deposits to your account</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h5>Check Your Account</h5>
                                <p>Look for the amounts in 1-2 business days</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h5>Enter Amounts</h5>
                                <p>Return here and enter the exact amounts</p>
                            </div>
                        </div>
                    </div>

                    @if(!$bankAccount->micro_transfer_sent_at)
                        <form action="{{ route('bank-accounts.start-micro-verification', $bankAccount) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-full">Start Micro-transfer Process</button>
                        </form>
                    @else
                        <div class="micro-status">
                            <div class="status-info">
                                <h5>✅ Micro-transfers Initiated</h5>
                                <p><strong>Sent:</strong> {{ $bankAccount->micro_transfer_sent_at->format('M d, Y \a\t g:i A') }}</p>
                                <p><strong>Expires:</strong> {{ $bankAccount->verification_expires_at->format('M d, Y \a\t g:i A') }}</p>
                                <p><strong>Attempts used:</strong> {{ $bankAccount->verification_attempts }}/3</p>
                                <p><em>Check your email for the verification amounts.</em></p>
                            </div>
                        </div>

                        <form action="{{ route('bank-accounts.verify', $bankAccount) }}" method="POST">
                            @csrf
                            <input type="hidden" name="verification_method" value="micro_transfer">

                            <div class="micro-amounts">
                                <p>Enter the two micro-transfer amounts you received:</p>
                                <div class="amount-inputs">
                                    <div class="form-group">
                                        <label for="micro_amount_1">First Amount (e.g., 0.23)</label>
                                        <input type="number" id="micro_amount_1" name="micro_amount_1" 
                                               step="0.01" min="0.01" max="0.99" required 
                                               placeholder="0.00" value="{{ old('micro_amount_1') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="micro_amount_2">Second Amount (e.g., 0.47)</label>
                                        <input type="number" id="micro_amount_2" name="micro_amount_2" 
                                               step="0.01" min="0.01" max="0.99" required 
                                               placeholder="0.00" value="{{ old('micro_amount_2') }}">
                                    </div>
                                </div>
                                @error('micro_amounts')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-full">Verify Amounts</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="back-action">
            <a href="{{ route('bank-accounts.index') }}" class="btn btn-secondary">Back to Bank Accounts</a>
        </div>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
}

.verification {
    padding: 2rem;
    background-color: #f9fafb;
    min-height: 70vh;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.account-info {
    margin-bottom: 2rem;
}

.account-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.account-card p {
    margin: 0.5rem 0;
    color: #374151;
}

.verification-methods {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.verification-methods h3 {
    padding: 1.5rem 1.5rem 0;
    margin: 0 0 1rem 0;
    color: #1f2937;
}

.method-tabs {
    display: flex;
    border-bottom: 1px solid #e5e7eb;
}

.tab-btn {
    flex: 1;
    padding: 1rem;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.2s;
}

.tab-btn.active {
    background-color: #3b82f6;
    color: white;
}

.tab-btn:hover:not(.active) {
    background-color: #f3f4f6;
}

.tab-content {
    display: none;
    padding: 1.5rem;
}

.tab-content.active {
    display: block;
}

.method-card {
    max-width: 100%;
}

.method-header h4 {
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.method-header p {
    color: #6b7280;
    margin: 0 0 1.5rem 0;
}

.file-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background-color 0.2s;
}

.file-upload-area:hover {
    border-color: #3b82f6;
    background-color: #f8fafc;
}

.upload-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.file-info {
    font-size: 0.875rem;
    color: #6b7280;
}

.file-name {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background-color: #f3f4f6;
    border-radius: 4px;
    font-weight: 500;
    display: none;
}

.requirements {
    background-color: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 1rem;
    margin: 1.5rem 0;
}

.requirements h5 {
    margin: 0 0 0.75rem 0;
    color: #0369a1;
}

.requirements ul {
    margin: 0;
    padding-left: 1rem;
}

.requirements li {
    color: #075985;
    margin-bottom: 0.25rem;
}

.micro-steps {
    margin: 1.5rem 0;
}

.step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.step-number {
    background-color: #3b82f6;
    color: white;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 1rem;
    flex-shrink: 0;
}

.step-content h5 {
    margin: 0 0 0.25rem 0;
    color: #1f2937;
}

.step-content p {
    margin: 0;
    color: #6b7280;
}

.amount-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.micro-status {
    background-color: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.status-info h5 {
    margin: 0 0 0.75rem 0;
    color: #0369a1;
}

.status-info p {
    margin: 0.25rem 0;
    color: #075985;
    font-size: 0.875rem;
}

.demo-bank-statement {
    background-color: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.demo-bank-statement h5 {
    margin: 0 0 0.75rem 0;
    color: #1f2937;
}

.demo-bank-statement em {
    color: #6b7280;
    font-size: 0.875rem;
}

.statement-entries {
    background-color: white;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    margin: 1rem 0;
    overflow: hidden;
}

.statement-entry {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f3f4f6;
    font-family: 'Courier New', monospace;
}

.statement-entry:last-child {
    border-bottom: none;
}

.entry-description {
    color: #374151;
    font-weight: 500;
}

.entry-amount {
    color: #059669;
    font-weight: bold;
}

.demo-note {
    margin: 0.75rem 0 0 0;
    color: #6b7280;
    font-size: 0.875rem;
    font-style: italic;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-block;
}

.btn-full {
    width: 100%;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background-color: #4b5563;
}

.back-action {
    margin-top: 1.5rem;
    text-align: center;
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.alert-info {
    background-color: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

.error-text {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

@media (max-width: 768px) {
    .method-tabs {
        flex-direction: column;
    }
    
    .amount-inputs {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

function updateFileName(input) {
    const fileName = document.getElementById('file-name');
    if (input.files.length > 0) {
        fileName.textContent = `Selected: ${input.files[0].name}`;
        fileName.style.display = 'block';
    } else {
        fileName.style.display = 'none';
    }
}
</script>

@include('includes.footer')