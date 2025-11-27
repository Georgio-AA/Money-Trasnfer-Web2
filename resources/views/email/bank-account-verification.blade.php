<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Bank Account</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif; background:#f5f7fb; margin:0; padding:0; }
        .container { max-width: 560px; margin: 24px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 24px rgba(16,24,40,0.08); }
        .header { background: linear-gradient(135deg,#4f46e5,#0ea5e9); color:#fff; padding: 20px 24px; }
        .header h1 { margin:0; font-size: 20px; }
        .content { padding: 24px; color:#111827; }
        .cta { display:inline-block; padding: 12px 18px; background:#2563eb; color:#fff !important; text-decoration:none; border-radius: 8px; font-weight:600; }
        .meta { margin-top:16px; font-size:12px; color:#6b7280; }
        .footer { padding: 16px 24px; background:#f9fafb; color:#6b7280; font-size:12px; }
        .account-card { margin: 16px 0; padding: 12px 14px; background:#f3f4f6; border-radius:8px; }
    </style>
    </head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Bank Account</h1>
        </div>
        <div class="content">
            <p>Hi,</p>
            <p>Please confirm ownership of the bank account below by clicking the button:</p>
            <div class="account-card">
                <div><strong>Bank:</strong> {{ $bankAccount->bank_name }}</div>
                <div><strong>Account:</strong> ****{{ substr($bankAccount->account_number, -4) }}</div>
                <div><strong>Currency:</strong> {{ $bankAccount->currency }}</div>
            </div>
            <p style="margin: 20px 0;">
                <a class="cta" href="{{ $verifyUrl }}">Verify this bank account</a>
            </p>
            <p class="meta">This link expires {{ optional($bankAccount->verification_expires_at)->diffForHumans() ?? 'in 7 days' }}.</p>
            <p>If you didn’t request this, you can safely ignore this email.</p>
        </div>
        <div class="footer">
            <p>Thanks,<br>The Team</p>
        </div>
    </div>
</body>
</html>
