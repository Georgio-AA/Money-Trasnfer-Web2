@include('includes.header')


<div class="profile-container" style="max-width: 600px; margin: 40px auto; padding: 20px; background: #f9f9f9; border-radius: 8px;">
    <h1 style="color: #333; margin-bottom: 30px;">User Profile</h1>
    
    @if($message = session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ $message }}
        </div>
    @endif
    
    <div class="profile-field" style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <label style="font-weight: bold; color: #555;">Name:</label>
        <p style="margin: 5px 0; color: #333;">{{ $user->name }}</p>
    </div>
    
    <div class="profile-field" style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <label style="font-weight: bold; color: #555;">Surname:</label>
        <p style="margin: 5px 0; color: #333;">{{ $user->surname }}</p>
    </div>
    
    <div class="profile-field" style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <label style="font-weight: bold; color: #555;">Age:</label>
        <p style="margin: 5px 0; color: #333;">{{ $user->age }}</p>
    </div>
    
    <div class="profile-field" style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <label style="font-weight: bold; color: #555;">Email:</label>
        <p style="margin: 5px 0; color: #333;">{{ $user->email }}</p>
    </div>
    
    <div class="profile-field" style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <label style="font-weight: bold; color: #555;">Phone:</label>
        <p style="margin: 5px 0; color: #333;">{{ $user->phone }}</p>
    </div>
    
    <div class="profile-field" style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <label style="font-weight: bold; color: #555;">Verified:</label>
        <p style="margin: 5px 0; color: #333;">
            @if($user->is_verified)
                <span style="color: #4CAF50; font-weight: bold;">✓ Yes</span>
            @else
                <span style="color: #FF9800; font-weight: bold;">✗ No</span>
            @endif
        </p>
    </div>
    
    <div class="profile-field" style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border-left: 4px solid #4CAF50;">
        <label style="font-weight: bold; color: #555;">Member Since:</label>
        <p style="margin: 5px 0; color: #333;">{{ $user->created_at->format('F j, Y') }}</p>
    </div>
    
    <div style="margin-top: 30px;">
        <a href="{{ route('profile.edit') }}" style="display: inline-block; background: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;">Edit Profile</a>
        <a href="{{ route('home') }}" style="display: inline-block; background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Back to Home</a>
    </div>
</div>

<!-- Password Change Section -->
<div class="profile-container" style="max-width: 600px; margin: 30px auto; padding: 20px; background: #f9f9f9; border-radius: 8px;">
    <h2 style="color: #333; margin-bottom: 30px;">Change Password</h2>
    
    <div style="background: white; padding: 20px; border-radius: 5px;">
        <p style="color: #555; margin-bottom: 20px;">For security, we'll send a password reset link to your email. Click the link to change your password.</p>
        
        <form action="{{ route('password.send.reset.email') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $user->email }}">
            <button type="submit" style="background: #FF9800; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Send Reset Email</button>
        </form>
    </div>
</div>

@include('includes.footer')
