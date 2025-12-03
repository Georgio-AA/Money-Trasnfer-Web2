@include('includes.header')

<section class="page-header">
    <h1>Request SwiftPay Card</h1>
    <p>Apply for your virtual or physical SwiftPay Card</p>
</section>

<section class="form-section">
    <div class="container">
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

        <div class="card-request-form-wrapper">
            <div class="form-card">
                <h2 class="form-title">Card Request Form</h2>
                
                <form method="POST" action="{{ route('card.request.store') }}" enctype="multipart/form-data" class="card-request-form">
                    @csrf

                    <!-- Account Balance Section -->
                    <div class="form-section-group">
                        <h3 class="section-heading">Current Account Information</h3>
                        
                        <div class="form-group">
                            <label for="balance">Current Account Balance</label>
                            <div class="read-only-field">
                                <input type="text" id="balance" readonly value="${{ number_format($userBalance, 2) }}" class="readonly-input">
                            </div>
                            <small class="hint">Your account balance is read-only</small>
                        </div>

                        <div class="form-group">
                            <label for="card_amount">Requested Card Amount</label>
                            <div class="read-only-field">
                                <input type="text" id="card_amount" readonly value="${{ number_format($userBalance, 2) }}" class="readonly-input">
                            </div>
                            <small class="hint">Your card amount will equal your current balance</small>
                        </div>
                    </div>

                    <!-- ID Upload Section -->
                    <div class="form-section-group">
                        <h3 class="section-heading">Government-Issued ID</h3>
                        
                        <div class="form-group">
                            <label for="id_image" class="required">Upload Clear Photo/Scan of ID *</label>
                            <div class="file-upload-wrapper">
                                <input 
                                    type="file" 
                                    name="id_image" 
                                    id="id_image" 
                                    required 
                                    accept=".jpg,.jpeg,.png" 
                                    class="file-input"
                                >
                                <div class="file-upload-hint">
                                    <p>📸 Accepted formats: JPG, PNG</p>
                                    <p>Maximum file size: 5MB</p>
                                    <p>Ensure the ID is clear and legible</p>
                                </div>
                                <div id="preview-container" class="preview-container" style="display: none;">
                                    <img id="preview-image" src="" alt="ID Preview" class="preview-image">
                                    <button type="button" class="remove-btn" onclick="removePreview()">✕ Remove</button>
                                </div>
                            </div>
                            @error('id_image')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="info-box">
                            <strong>ℹ️ ID Requirements:</strong>
                            <ul>
                                <li>Valid government-issued ID (Passport, Driver's License, National ID, etc.)</li>
                                <li>Photo must be clear and legible</li>
                                <li>All details must be visible</li>
                                <li>No expired IDs accepted</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Submit Card Request
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                    <div class="terms-notice">
                        <p>By submitting this request, you agree to our terms of service and acknowledge that the information provided is accurate and truthful.</p>
                    </div>
                </form>
            </div>

            <!-- Info Panel -->
            <div class="info-panel">
                <h3>About SwiftPay Card</h3>
                <div class="info-item">
                    <h4>🎯 What is SwiftPay Card?</h4>
                    <p>SwiftPay Card is a virtual or physical card that allows you to make payments and transfers directly from your account balance.</p>
                </div>
                <div class="info-item">
                    <h4>⏱️ Processing Time</h4>
                    <p>Your request will be reviewed within 24-48 hours. We'll notify you via email once your card has been approved or if additional information is needed.</p>
                </div>
                <div class="info-item">
                    <h4>🔒 Security</h4>
                    <p>Your ID and personal information are encrypted and stored securely. We never share your information with third parties.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card-request-form-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin: 30px 0;
    }

    .form-card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 30px;
        color: #333;
    }

    .form-section-group {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-section-group:last-of-type {
        border-bottom: none;
    }

    .section-heading {
        font-size: 16px;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        font-size: 14px;
    }

    .required::after {
        content: '*';
        color: #e74c3c;
        margin-left: 4px;
    }

    .read-only-field {
        margin-bottom: 8px;
    }

    .readonly-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f8f9fa;
        font-size: 14px;
        color: #333;
        font-weight: 600;
    }

    .readonly-input:disabled {
        cursor: not-allowed;
    }

    .hint {
        display: block;
        color: #666;
        font-size: 13px;
        margin-top: 4px;
    }

    .file-upload-wrapper {
        border: 2px dashed #667eea;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background-color: #f8f9ff;
        position: relative;
    }

    .file-input {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
    }

    .file-upload-hint {
        color: #666;
        font-size: 13px;
    }

    .file-upload-hint p {
        margin: 5px 0;
    }

    .preview-container {
        margin-top: 15px;
        position: relative;
        display: inline-block;
    }

    .preview-image {
        max-width: 100%;
        max-height: 300px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .remove-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .remove-btn:hover {
        background: #c0392b;
    }

    .info-box {
        background-color: #e8f4f8;
        border-left: 4px solid #3498db;
        padding: 15px;
        border-radius: 4px;
        font-size: 14px;
        margin-top: 15px;
    }

    .info-box strong {
        display: block;
        margin-bottom: 10px;
        color: #0288d1;
    }

    .info-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .info-box li {
        margin: 5px 0;
        color: #333;
    }

    .form-actions {
        margin-top: 30px;
        display: flex;
        gap: 15px;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background-color: #5568d3;
    }

    .btn-secondary {
        background-color: #e0e0e0;
        color: #333;
    }

    .btn-secondary:hover {
        background-color: #d0d0d0;
    }

    .btn-lg {
        padding: 15px 40px;
        font-size: 16px;
    }

    .terms-notice {
        background-color: #f0f0f0;
        padding: 15px;
        border-radius: 5px;
        font-size: 12px;
        color: #666;
        margin-top: 20px;
    }

    .info-panel {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .info-panel h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
    }

    .info-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-item h4 {
        font-size: 14px;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 8px;
    }

    .info-item p {
        font-size: 13px;
        color: #666;
        margin: 0;
        line-height: 1.5;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .card-request-form-wrapper {
            grid-template-columns: 1fr;
        }

        .info-panel {
            position: relative;
            top: auto;
        }
    }
</style>

<script>
    const fileInput = document.getElementById('id_image');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
                previewContainer.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }
    });

    function removePreview() {
        previewContainer.style.display = 'none';
        fileInput.value = '';
    }
</script>

@include('includes.footer')
