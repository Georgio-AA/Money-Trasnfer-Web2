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

@include('includes.footer')
