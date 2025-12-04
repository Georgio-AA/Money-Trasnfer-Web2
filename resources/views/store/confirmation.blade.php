@include('includes.header')

<style>
    .confirmation-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 1rem;
        text-align: center;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .confirmation-hero .check-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        animation: scaleIn 0.6s ease-in-out;
    }

    .confirmation-hero h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .confirmation-hero p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    .order-detail-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .order-detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
    }

    .product-badge {
        display: inline-block;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .price-highlight {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .code-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 1rem;
        padding: 2rem;
        margin: 2rem 0;
        border: 2px dashed #dee2e6;
    }

    .code-section .section-title {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .code-input-wrapper {
        position: relative;
        margin-bottom: 1rem;
    }

    .code-input {
        width: 100%;
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 0.75rem;
        padding: 1rem;
        font-family: 'Courier New', monospace;
        font-size: 1.15rem;
        letter-spacing: 0.15em;
        text-align: center;
        font-weight: 600;
        color: #212529;
        transition: all 0.3s ease;
    }

    .code-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .copy-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .copy-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .copy-status {
        display: inline-block;
        margin-top: 0.5rem;
        font-weight: 600;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-badge.completed {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .status-badge.failed {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin: 2rem 0;
    }

    .action-btn {
        padding: 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .action-btn.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .action-btn.primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .action-btn.secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .action-btn.secondary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-3px);
    }

    .info-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 1rem;
        margin-top: 2rem;
    }

    .info-banner .banner-title {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .info-banner p {
        margin: 0;
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .confirmation-hero h2 {
            font-size: 1.8rem;
        }

        .action-buttons {
            grid-template-columns: 1fr;
        }

        .code-section {
            padding: 1.5rem;
        }
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Success Hero Section -->
            <div class="confirmation-hero">
                <div class="check-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Purchase Successful!</h2>
                <p>Your digital service has been activated and is ready to use.</p>
            </div>

            <!-- Order Details Card -->
            <div class="order-detail-card">
                <div class="detail-row">
                    <span class="detail-label">Order ID</span>
                    <code style="font-weight: 600; color: #007bff;">#{{ $order->id }}</code>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Date</span>
                    <span class="detail-value">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    @if($order->status === 'completed')
                        <span class="status-badge completed">
                            <i class="fas fa-check-circle me-1"></i> Completed
                        </span>
                    @elseif($order->status === 'pending')
                        <span class="status-badge pending">
                            <i class="fas fa-hourglass me-1"></i> Pending
                        </span>
                    @else
                        <span class="status-badge failed">
                            <i class="fas fa-times-circle me-1"></i> Failed
                        </span>
                    @endif
                </div>
            </div>

            <!-- Product Details Card -->
            <div class="order-detail-card">
                <div class="product-badge">
                    <i class="fas fa-cube me-1"></i> {{ ucfirst(str_replace('_', ' ', $order->product->category)) }}
                </div>
                <h5 style="font-weight: 700; margin-bottom: 0.5rem; color: #212529;">{{ $order->product->name }}</h5>
                <p style="color: #6c757d; margin-bottom: 1rem;">
                    <i class="fas fa-building me-1"></i> {{ $order->product->provider }}
                </p>
                @if($order->product->description)
                    <p style="color: #495057; font-size: 0.95rem;">{{ $order->product->description }}</p>
                @endif
            </div>

            <!-- Price Card -->
            <div class="order-detail-card">
                <div class="detail-row">
                    <span class="detail-label">Amount Charged</span>
                    <span class="price-highlight">${{ number_format($order->price_at_purchase, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Remaining Balance</span>
                    <span style="font-size: 1.1rem; font-weight: 600; color: #11998e;">${{ number_format(session('user')['balance'] ?? 0, 2) }}</span>
                </div>
            </div>

            <!-- Redemption Code Section -->
            <div class="code-section">
                <div class="section-title">
                    <i class="fas fa-key me-1"></i> Your Redemption Code
                </div>
                <div class="code-input-wrapper">
                    <input 
                        type="text" 
                        class="code-input"
                        id="redemption-code"
                        value="{{ $formattedCode }}" 
                        readonly
                    >
                </div>
                <button 
                    class="copy-btn w-100" 
                    id="copy-btn"
                    title="Copy to clipboard"
                >
                    <i class="fas fa-copy me-2"></i> Copy Code
                </button>
                <div style="text-align: center;">
                    <small class="copy-status text-success" id="copy-status"></small>
                </div>
                <p style="text-align: center; color: #6c757d; font-size: 0.85rem; margin-top: 1rem;">
                    <i class="fas fa-info-circle me-1"></i> 
                    Save this code for your records. You'll need it to redeem your service.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('store.index') }}" class="action-btn primary">
                    <i class="fas fa-shopping-bag"></i> Back to Store
                </a>
                <a href="{{ route('store.my-purchases') }}" class="action-btn secondary">
                    <i class="fas fa-history"></i> My Purchases
                </a>
            </div>

            <!-- Support Info Banner -->
            <div class="info-banner">
                <div class="banner-title">
                    <i class="fas fa-headset me-2"></i> Need Assistance?
                </div>
                <p>
                    If you encounter any issues redeeming your code, our support team is here to help. 
                    Contact us at <strong>support@example.com</strong>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('copy-btn').addEventListener('click', function() {
    const codeInput = document.getElementById('redemption-code');
    const statusEl = document.getElementById('copy-status');
    
    // Copy to clipboard using modern API
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(codeInput.value).then(() => {
            statusEl.textContent = '✓ Copied to clipboard!';
            statusEl.className = 'copy-status text-success';
            
            setTimeout(() => {
                statusEl.textContent = '';
            }, 2000);
        }).catch(() => {
            // Fallback for older browsers
            fallbackCopy(codeInput, statusEl);
        });
    } else {
        fallbackCopy(codeInput, statusEl);
    }
});

function fallbackCopy(codeInput, statusEl) {
    codeInput.select();
    document.execCommand('copy');
    
    statusEl.textContent = '✓ Copied to clipboard!';
    statusEl.className = 'copy-status text-success';
    
    setTimeout(() => {
        statusEl.textContent = '';
    }, 2000);
}

// Auto-select code on page load for easy copying
window.addEventListener('load', function() {
    const codeInput = document.getElementById('redemption-code');
    if (codeInput) {
        codeInput.select();
    }
});
</script>

@include('includes.footer')
