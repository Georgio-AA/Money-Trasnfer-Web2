<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Welcome, {{ $user->name }}!</h2>
        <p>Thank you for registering with us. Please verify your email within <strong>3 days</strong> by clicking the button below.</p>

        <p style="text-align: center;">
            <a href="{{ url('/verify/' . $user->verification_token) }}" 
               style="background-color: #4CAF50; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px;">
               Verify My Email
            </a>
        </p>

    <p>This link will expire on <strong>{{ $user->verification_due->format('F j, Y g:i A') }}</strong>.</p>

        <p>If you did not create this account, you can safely ignore this message.</p>
        <hr style="border:none; border-top:1px solid #eee;">
        <p style="text-align:center; color:#777;">&copy; {{ date('Y') }} MyApp. All rights reserved.</p>
    </div>
</body>
</html>
