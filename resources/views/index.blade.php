
@include('includes.header')
<section class="hero">
    <h1>Fast, Secure, and Affordable Money Transfers Worldwide</h1>
    <p>Send money to your loved ones anywhere in the world with the best exchange rates and lowest fees.</p>
    @if(session()->has('user'))
        <a href="{{ route('transfers.create') }}" class="cta-btn">Start Sending Now</a>
    @else
        <a href="{{ route('login') }}" class="cta-btn">Start Sending Now</a>
    @endif
</section>

<section class="features">
    <h2>Why Choose SwiftPay?</h2>
    <div class="grid">
        <div><h3>Low Fees</h3><p>Save more with our competitive rates and transparent pricing.</p></div>
        <div><h3>Fast Transfers</h3><p>Money delivered in minutes, not days. Track every step.</p></div>
        <div><h3>Secure Transactions</h3><p>Bank-level encryption and security for peace of mind.</p></div>
        <div><h3>Global Reach</h3><p>Send money to over 200 countries and territories worldwide.</p></div>
    </div>
</section>

<section class="steps">
    <h2>How It Works</h2>
    <div class="grid">
        <div><h3>Sign Up</h3><p>Create your free account in just a few minutes.</p></div>
        <div><h3>Add Account</h3><p>Link your bank account or payment method securely.</p></div>
        <div><h3>Send & Track</h3><p>Transfer money and track it in real-time.</p></div>
    </div>
</section>

<section class="swiftpay-card-section">
    <div class="card-container">
        <div class="card-content">
            <div class="card-badge">New Feature</div>
            <h2>Get Your SwiftPay Card</h2>
            <p class="card-description">Access your funds instantly with our virtual or physical card. Spend smartly, transfer securely.</p>
            
            <div class="card-features">
                <div class="feature-item">
                    <div class="feature-icon">💳</div>
                    <h4>Virtual & Physical Cards</h4>
                    <p>Choose between instant virtual card or physical card delivery</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🌍</div>
                    <h4>Global Acceptance</h4>
                    <p>Use your card at millions of merchants worldwide</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔒</div>
                    <h4>Maximum Security</h4>
                    <p>Bank-level encryption and fraud protection included</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">⚡</div>
                    <h4>Instant Funding</h4>
                    <p>Automatically loaded with your account balance</p>
                </div>
            </div>

            @if(session()->has('user'))
                <a href="{{ route('card.request.create') }}" class="card-cta-btn">Request Your Card Now</a>
            @else
                <a href="{{ route('login') }}" class="card-cta-btn">Login to Request Card</a>
            @endif
        </div>
        
        <div class="card-visual">
            <div class="card-display">
                <div class="card-front">
                    <div class="card-logo">SwiftPay</div>
                    <div class="card-number">•••• •••• •••• 4895</div>
                    <div class="card-holder">
                        <div><small>Cardholder Name</small></div>
                        <div><small>MM/YY</small></div>
                    </div>
                </div>
                <div class="card-back">
                    <div class="card-strip"></div>
                    <div class="card-cvv">CVV</div>
                </div>
            </div>
            <div class="card-perks">
                <div class="perk">✓ No Annual Fee</div>
                <div class="perk">✓ 24/7 Support</div>
                <div class="perk">✓ Instant Notifications</div>
                <div class="perk">✓ Easy Management</div>
            </div>
        </div>
    </div>
</section>

<section class="testimonials">
    <h2>What Our Customers Say</h2>
    <div class="grid">
        <div><p>"SwiftPay has made sending money so easy and affordable!"</p><h4>Michael Chen - Singapore</h4></div>
        <div><p>"Low fees and great service!"</p><h4>Priya Sharma - United Kingdom</h4></div>
        <div><p>"Best exchange rates ever!"</p><h4>Sarah Johnson - United States</h4></div>
    </div>
</section>

@include('includes.footer')

<style>
    /* SwiftPay Card Section Styles */
    .swiftpay-card-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 20px;
        margin: 60px 0;
    }

    .card-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
    }

    .card-content {
        color: white;
    }

    .card-badge {
        display: inline-block;
        background-color: rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .card-content h2 {
        font-size: 40px;
        font-weight: 700;
        margin: 0 0 15px 0;
        line-height: 1.2;
    }

    .card-description {
        font-size: 18px;
        margin-bottom: 30px;
        opacity: 0.95;
        line-height: 1.6;
    }

    .card-features {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 40px;
    }

    .feature-item {
        background-color: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .feature-icon {
        font-size: 32px;
        margin-bottom: 12px;
        display: block;
    }

    .feature-item h4 {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 8px 0;
    }

    .feature-item p {
        font-size: 14px;
        margin: 0;
        opacity: 0.9;
        line-height: 1.5;
    }

    .card-cta-btn {
        display: inline-block;
        background-color: white;
        color: #667eea;
        padding: 15px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .card-cta-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .card-visual {
        perspective: 1000px;
    }

    .card-display {
        position: relative;
        width: 100%;
        max-width: 350px;
        height: 220px;
        margin-bottom: 30px;
        cursor: pointer;
        transform-style: preserve-3d;
        transition: transform 0.6s;
    }

    .card-display:hover .card-front {
        transform: rotateY(-180deg);
    }

    .card-display:hover .card-back {
        transform: rotateY(0deg);
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        padding: 25px;
        box-sizing: border-box;
        font-family: 'Arial', sans-serif;
    }

    .card-front {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        justify-content: space-between;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .card-back {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
        transform: rotateY(180deg);
        justify-content: center;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .card-logo {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .card-number {
        font-size: 24px;
        letter-spacing: 3px;
        font-weight: 600;
        margin: 20px 0;
    }

    .card-holder {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        opacity: 0.8;
    }

    .card-strip {
        width: 100%;
        height: 40px;
        background-color: #000;
        margin-bottom: 20px;
    }

    .card-cvv {
        background-color: #fff;
        color: #000;
        padding: 5px 15px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .card-perks {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .perk {
        background-color: rgba(255, 255, 255, 0.15);
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .card-content h2 {
            font-size: 28px;
        }

        .card-description {
            font-size: 16px;
        }

        .card-features {
            grid-template-columns: 1fr;
        }

        .card-display {
            max-width: 100%;
            margin: 0 auto 30px;
        }

        .card-perks {
            grid-template-columns: 1fr;
        }
    }
</style>
