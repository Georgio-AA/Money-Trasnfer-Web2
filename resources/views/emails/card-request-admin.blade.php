<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Request Submission</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .section {
            margin: 20px 0;
        }
        .section-title {
            font-weight: 600;
            color: #667eea;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .amount-highlight {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }
        .alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }
        .alert-text {
            color: #856404;
        }
        .cta-button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: 600;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 New Card Request Submitted</h1>
            <p>Admin Review Required</p>
        </div>

        <div class="content">
            <p>A new SwiftPay Card request has been submitted and requires your review.</p>

            <div class="section">
                <div class="section-title">User Information</div>
                <div class="info-row">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value">{{ $userName }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $userEmail }}</span>
                </div>
                @if($userPhone)
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $userPhone }}</span>
                </div>
                @endif
            </div>

            <div class="section">
                <div class="section-title">Card Request Details</div>
                <div class="info-row">
                    <span class="info-label">Requested Card Amount:</span>
                    <span class="info-value amount-highlight">${{ number_format($cardAmount, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Submission Date:</span>
                    <span class="info-value">{{ date('F d, Y \a\t g:i A') }}</span>
                </div>
            </div>

            <div class="alert">
                <div class="alert-text">
                    <strong>⚠️ Action Required:</strong> Please review the submitted ID image and approve or reject this card request in the admin dashboard.
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $reviewLink }}" class="cta-button">Review Request in Dashboard</a>
            </div>

            <p style="color: #666; font-size: 14px; margin-top: 30px;">
                <strong>Next Steps:</strong>
                <br>1. Click the button above to access the admin dashboard
                <br>2. Review the uploaded ID image
                <br>3. Approve or reject the request
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} SWIFTPAY. All rights reserved.<br>
            This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
