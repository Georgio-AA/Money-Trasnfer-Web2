@include('includes.header')

<section class="password-request-container">
    <div class="password-request-card">

        <h2 class="title">Reset Your Password</h2>
        <p class="subtitle">Enter your email address and we'll send you a link to reset your password.</p>

        @if (session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('password.send.reset.email') }}" class="password-form">
            @csrf

            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your registered email" required>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Send Reset Link</button>

            <p class="back-to-login">
                Remember your password?
                <a href="{{ route('login') }}">Back to login</a>
            </p>
        </form>

    </div>
</section>

<style>
/* Page Layout */
.password-request-container {
    display: flex;
    justify-content: center;
    padding: 40px 15px;
    background: #f4f7fb;
    min-height: 100vh;
}

.password-request-card {
    background: #ffffff;
    padding: 35px 40px;
    width: 420px;
    border-radius: 12px;
    box-shadow: 0px 6px 20px rgba(0,0,0,0.08);
}

/* Title */
.title {
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.subtitle {
    text-align: center;
    font-size: 14px;
    color: #7f8c8d;
    margin-bottom: 25px;
    line-height: 1.6;
}

/* Alerts */
.alert {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 14px;
    font-weight: bold;
    text-align: center;
}

.alert.error {
    background: #ffe4e4;
    color: #d63939;
    border: 1px solid #ffcccc;
}

.alert.success {
    background: #e5f9e7;
    color: #2f8d4e;
    border: 1px solid #bff3c7;
}

/* Form Inputs */
.password-form .input-group {
    margin-bottom: 18px;
}

.password-form label {
    font-size: 14px;
    font-weight: bold;
    color: #34495e;
    display: block;
    margin-bottom: 6px;
}

.password-form input {
    width: 100%;
    padding: 11px;
    border-radius: 6px;
    border: 1px solid #cfd6df;
    background: #fafbff;
    font-size: 14px;
    box-sizing: border-box;
}

.password-form input:focus {
    border-color: #4a90e2;
    outline: none;
    box-shadow: 0 0 4px rgba(74,144,226,0.3);
}

/* Error Text */
.error-text {
    color: #d63939;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    background: #4a8df6;
    padding: 12px;
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
    margin-top: 10px;
}

.submit-btn:hover {
    background: #2f6fe0;
}

.submit-btn:active {
    transform: translateY(1px);
}

/* Back to Login */
.back-to-login {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.back-to-login a {
    color: #4a8df6;
    font-weight: bold;
    text-decoration: none;
}

.back-to-login a:hover {
    text-decoration: underline;
}
</style>

@include('includes.footer')
