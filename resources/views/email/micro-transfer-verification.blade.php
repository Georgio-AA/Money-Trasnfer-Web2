<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Account Verification - Micro-transfers Initiated</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .verification-icon {
            font-size: 48px;
            margin: 20px 0;
        }
        .account-details {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
        }
        .steps {
            background-color: #f0f9ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .step-number {
            background-color: #3b82f6;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
            font-size: 12px;
        }
        .step-content {
            flex: 1;
        }
        .verification-link {
            display: block;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            text-align: center;
            font-weight: 500;
            margin: 20px 0;
            transition: background-color 0.2s;
        }
        .verification-link:hover {
            background-color: #2563eb;
        }
        .important-note {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .contact-info {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 15px;
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        .amounts-highlight {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .amounts-box {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 15px 0;
            flex-wrap: wrap;
        }
        .amount-item {
            background-color: white;
            border: 2px solid #0ea5e9;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .amount-label {
            display: block;
            color: #374151;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .amount-value {
            display: block;
            color: #0ea5e9;
            font-size: 24px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">SwiftPay</div>
            <div class="verification-icon">💰</div>
            <h1 style="margin: 0; color: #1f2937;">Micro-transfers Initiated</h1>
            <p style="margin: 10px 0 0 0; color: #6b7280;">Your bank account verification is in progress</p>
        </div>

        <p>Hello {{ $bankAccount->user->name }},</p>

        <p>We've successfully initiated micro-transfers to verify your bank account. Two small deposits will be sent to your account within 1-2 business days.</p>

        <div class="account-details">
            <h3 style="margin-top: 0;">Account Being Verified:</h3>
            <p><strong>Bank:</strong> {{ $bankAccount->bank_name }}</p>
            <p><strong>Account:</strong> ****{{ substr($bankAccount->account_number, -4) }}</p>
            <p><strong>Currency:</strong> {{ $bankAccount->currency }}</p>
            <p style="margin-bottom: 0;"><strong>Expected Arrival:</strong> {{ $estimatedArrival }}</p>
        </div>

        <div class="steps">
            <h3 style="margin-top: 0; color: #1f2937;">Next Steps:</h3>
            
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <strong>Check Your Account</strong><br>
                    Look for these two deposits in your bank account within 1-2 business days.
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <strong>Micro-Transfer Amounts (Demo)</strong><br>
                    For testing purposes, the amounts sent are: <strong>${{ number_format($microAmounts['amount1'], 2) }}</strong> and <strong>${{ number_format($microAmounts['amount2'], 2) }}</strong>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <strong>Complete Verification</strong><br>
                    Return to SwiftPay and enter these exact amounts to complete verification.
                </div>
            </div>
        </div>

        <a href="{{ route('bank-accounts.verify-form', $bankAccount) }}" class="verification-link">
            Complete Verification
        </a>

        <div class="amounts-highlight">
            <h4 style="margin-top: 0; color: #1f2937;">🔢 Your Verification Amounts (Demo)</h4>
            <div class="amounts-box">
                <div class="amount-item">
                    <span class="amount-label">First Amount:</span>
                    <span class="amount-value">${{ number_format($microAmounts['amount1'], 2) }}</span>
                </div>
                <div class="amount-item">
                    <span class="amount-label">Second Amount:</span>
                    <span class="amount-value">${{ number_format($microAmounts['amount2'], 2) }}</span>
                </div>
            </div>
            <p style="margin: 10px 0 0 0; color: #6b7280; font-size: 14px; font-style: italic;">
                Enter these exact amounts on the verification page to complete the process.
            </p>
        </div>

        <div class="important-note">
            <h4 style="margin-top: 0; color: #92400e;">⚠️ Important Notes:</h4>
            <ul style="margin-bottom: 0;">
                <li>The amounts will be between $0.01 and $0.99</li>
                <li>Both amounts will be automatically refunded after verification</li>
                <li>If you don't see the deposits after 3 business days, contact support</li>
                <li>This verification link expires in 7 days</li>
            </ul>
        </div>

        <p>Once verified, you'll be able to enjoy:</p>
        <ul>
            <li>✅ Faster money transfers</li>
            <li>✅ Higher transfer limits</li>
            <li>✅ Lower fees for verified accounts</li>
            <li>✅ Enhanced security</li>
        </ul>

        <div class="contact-info">
            <p><strong>Need Help?</strong></p>
            <p>If you have any questions or need assistance, contact our support team:</p>
            <p>📧 support@swiftpay.com | 📞 1-800-SWIFTPAY</p>
            <p>Support hours: Monday-Friday 8AM-8PM EST</p>
        </div>

        <div class="footer">
            <p>This email was sent by SwiftPay regarding your bank account verification.</p>
            <p>If you didn't request this verification, please contact us immediately.</p>
            <p>&copy; {{ date('Y') }} SwiftPay. All rights reserved.</p>
        </div>
    </div>
</body>
</html>