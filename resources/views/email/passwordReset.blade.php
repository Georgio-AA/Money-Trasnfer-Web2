<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Password Reset Request</h2>
        <p>Hello {{ $user->name }},</p>
        <p>We received a request to reset your password. Click the button below to proceed:</p>

        <p style="text-align: center;">
            <a href="{{ url('/reset-password/' . $resetToken) }}" 
               style="background-color: #FF9800; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
               Reset Password
            </a>
        </p>

        <p style="color: #666; font-size: 14px;">This link will expire in <strong>1 hour</strong>.</p>
        <p style="color: #666; font-size: 14px;">If you did not request a password reset, please ignore this email.</p>
        
        <hr style="border:none; border-top:1px solid #eee; margin: 20px 0;">
        <p style="text-align:center; color:#777; font-size: 12px;">&copy; {{ date('Y') }} SwiftPay. All rights reserved.</p>
    </div>
</body>
</html>
