@include('includes.header')

<div class="request-password-reset-container" style="max-width: 600px; margin: 40px auto; padding: 20px; background: #f9f9f9; border-radius: 8px;">
    <h1 style="color: #333; margin-bottom: 30px;">Request Password Reset</h1>
    
    <div style="background: white; padding: 20px; border-radius: 5px;">
        <p style="color: #555; margin-bottom: 20px;">We'll send a password reset link to your email address.</p>
        
        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('password.send.reset.email') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 8px;">Email Address</label>
                <input type="email" name="email" value="{{ session('user.email') ?? old('email') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            </div>
            
            <div style="margin-top: 30px;">
                <button type="submit" style="background: #FF9800; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px;">Send Reset Link</button>
                <a href="{{ route('profile') }}" style="display: inline-block; background: #999; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancel</a>
            </div>
        </form>
    </div>
</div>

@include('includes.footer')
