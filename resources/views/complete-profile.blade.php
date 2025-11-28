<h2>Complete Your Profile</h2>

<form method="POST" action="{{ route('profile.complete') }}">
    @csrf

    <label>Phone:</label>
    <input type="text" name="phone" required>

    <label>Age:</label>
    <input type="number" name="age" required>

    <button type="submit">Continue</button>
</form>
