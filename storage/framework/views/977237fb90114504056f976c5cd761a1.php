<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Transfer Notification</title>
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
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .transfer-details {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .amount-highlight {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        .reference {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
            text-align: center;
            margin: 15px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
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
        .info-box {
            background-color: #e8f4f8;
            border-left: 4px solid #0288d1;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Money Transfer Received</h1>
            <p>You have received a money transfer</p>
        </div>

        <div class="content">
            <div class="greeting">
                <p>Hi <?php echo e($beneficiaryName); ?>,</p>
                <p>Great news! You have received a money transfer from <strong><?php echo e($senderName); ?></strong> via SWIFTPAY.</p>
            </div>

            <div class="transfer-details">
                <div class="detail-row">
                    <span class="detail-label">Amount Received:</span>
                    <span class="detail-value amount-highlight"><?php echo e($currency); ?> <?php echo e(number_format($amount, 2)); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">From:</span>
                    <span class="detail-value"><?php echo e($senderName); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Service:</span>
                    <span class="detail-value">SWIFTPAY</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Received Date:</span>
                    <span class="detail-value"><?php echo e(date('F d, Y \a\t g:i A')); ?></span>
                </div>
            </div>

            <div class="reference">
                <strong>Transfer Reference:</strong><br>
                <?php echo e($transferReference); ?>

            </div>

            <div class="info-box">
                <strong>📝 Next Steps:</strong><br>
                Please log in to your SWIFTPAY account to view the full transfer details and manage your funds. If you have any questions, our support team is here to help!
            </div>

            <div style="text-align: center;">
                <a href="#" class="cta-button">View Transfer Details</a>
            </div>

            <p style="color: #666; font-size: 14px; margin-top: 30px;">
                <strong>Need help?</strong> If you didn't expect this transfer or have any concerns, please contact our support team immediately.
            </p>
        </div>

        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> SWIFTPAY. All rights reserved.<br>
            This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/emails/transfer-notification.blade.php ENDPATH**/ ?>