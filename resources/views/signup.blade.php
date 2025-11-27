

@include('includes.header')

<section class="form-section">
    <h2>Create Your SwiftPay Account</h2>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color:red">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('signup.submit') }}">
        @csrf

        <label for="name">First Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>

        <label for="surname">Surname:</label>
        <input type="text" name="surname" id="surname" value="{{ old('surname') }}" required>

         <label for="surname">Phone Number:</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>

        <label for="age">Age:</label>
        <input type="number" name="age" id="age" value="{{ old('age') }}" required>

        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>

        <button type="submit">Sign Up</button>
        <p class="note">Already have an account? <a href="{{ route('login') }}">Login</a>.</p>
    </form>
</section>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">



<style>
    .social-login-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        margin-top: 25px;
    }

    .social-btn {
        display: block;
        width: 200px;         /* Adjust width as needed */
        text-align: center;
        padding: 12px;
        border-radius: 6px;
        color: white;
        font-weight: bold;
        text-decoration: none;
        font-size: 15px;
        transition: 0.2s;
    }

    .google-btn {
        background-color: #db4437;
    }
    .google-btn:hover {
        background-color: #b33428;
    }

    .facebook-btn {
        background-color: #1877F2;
    }
    .facebook-btn:hover {
        background-color: #0f59c2;
    }

    .social-btn i {
        margin-right: 8px;
    }
</style>

<div class="social-login-wrapper">
    <a href="{{ route('google.login') }}" class="social-btn google-btn">
        <i class="fab fa-google"></i> Sign up with Google
    </a>

    <a href="{{ route('facebook.login') }}" class="social-btn facebook-btn">
        <i class="fab fa-facebook"></i> Sign up with Facebook
    </a>
</div>



@include('includes.footer')
