@include('includes.header')

<section class="form-section">
    <h2>Login to Your Account</h2>

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
        <p class="note">Don't have an account? <a href="{{ route('signup') }}">Sign up here</a>.</p>
    </form>
</section>

@include('includes.footer')
