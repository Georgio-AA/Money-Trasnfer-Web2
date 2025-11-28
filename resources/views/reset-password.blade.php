@include('includes.header')

<div class="reset-password-container" style="max-width: 600px; margin: 40px auto; padding: 20px; background: #f9f9f9; border-radius: 8px;">
    <h1 style="color: #333; margin-bottom: 30px;">Reset Your Password</h1>
    
    <div style="background: white; padding: 20px; border-radius: 5px;">
        <p style="color: #555; margin-bottom: 20px;">Enter your new password below. It must contain uppercase, lowercase, number, and special character.</p>
        
        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('reset.password') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 8px;">New Password</label>
                <input type="password" name="new_password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
                <small style="color: #666; display: block; margin-top: 5px;">Must contain uppercase, lowercase, number, and special character (@$!%*?&)</small>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 8px;">Confirm Password</label>
                <input type="password" name="new_password_confirmation" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            </div>
            
            <div style="margin-top: 30px;">
                <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px;">Reset Password</button>
                <a href="{{ route('login') }}" style="display: inline-block; background: #999; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Back to Login</a>
            </div>
        </form>
    </div>
</div>

@include('includes.footer')
