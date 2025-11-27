<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Receipt #<?php echo e($transfer->id); ?> - SwiftPay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f3f4f6;
            padding: 2rem;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .receipt-header h1 {
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .receipt-header p {
            opacity: 0.9;
            font-size: 1.125rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 1rem;
            font-size: 1rem;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .receipt-body {
            padding: 2rem;
        }

        .section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.125rem;
            color: #1f2937;
            font-weight: 500;
        }

        .info-value.large {
            font-size: 1.5rem;
            color: #667eea;
            font-weight: 700;
        }

        .total-box {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .total-row:last-child {
            margin-bottom: 0;
            padding-top: 0.75rem;
            border-top: 2px solid #d1d5db;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .receipt-footer {
            background: #f9fafb;
            padding: 2rem;
            text-align: center;
            color: #6b7280;
        }

        .receipt-footer p {
            margin: 0.5rem 0;
        }

        .transaction-id {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 6px;
            text-align: center;
            margin: 1rem 0;
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            color: #374151;
            border: 2px dashed #d1d5db;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }

            .actions {
                display: none;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>SwiftPay</h1>
            <p>Transfer Receipt</p>
            <span class="status-badge status-<?php echo e($transfer->status); ?>">
                <?php echo e(ucfirst($transfer->status)); ?>

            </span>
        </div>

        <div class="receipt-body">
            <div class="transaction-id">
                <strong>Transaction ID:</strong> <?php echo e(strtoupper(str_pad($transfer->id, 10, '0', STR_PAD_LEFT))); ?>

            </div>

            <div class="section">
                <h2 class="section-title">Transfer Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Date & Time</span>
                        <span class="info-value"><?php echo e($transfer->created_at->format('M d, Y H:i')); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Transfer Speed</span>
                        <span class="info-value"><?php echo e(ucfirst(str_replace('_', ' ', $transfer->transfer_speed))); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Payout Method</span>
                        <span class="info-value"><?php echo e(ucfirst(str_replace('_', ' ', $transfer->payout_method))); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Destination Country</span>
                        <span class="info-value"><?php echo e($transfer->destination_country); ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Sender Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value"><?php echo e($transfer->sender->name); ?> <?php echo e($transfer->sender->surname ?? ''); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?php echo e($transfer->sender->email); ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Recipient Information</h2>
                <?php if($transfer->beneficiary): ?>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Name</span>
                            <span class="info-value"><?php echo e($transfer->beneficiary->full_name); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value"><?php echo e($transfer->beneficiary->phone_number); ?></span>
                        </div>
                        <?php if($transfer->beneficiary->email): ?>
                            <div class="info-item">
                                <span class="info-label">Email</span>
                                <span class="info-value"><?php echo e($transfer->beneficiary->email); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <span class="info-label">Country</span>
                            <span class="info-value"><?php echo e($transfer->beneficiary->country); ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <p>No beneficiary information available</p>
                <?php endif; ?>
            </div>

            <div class="section">
                <h2 class="section-title">Amount Breakdown</h2>
                <div class="total-box">
                    <div class="total-row">
                        <span>Send Amount</span>
                        <span><?php echo e($transfer->source_currency); ?> <?php echo e(number_format($transfer->amount, 2)); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Transfer Fee</span>
                        <span><?php echo e($transfer->source_currency); ?> <?php echo e(number_format($transfer->transfer_fee, 2)); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Total Paid</span>
                        <span class="large"><?php echo e($transfer->source_currency); ?> <?php echo e(number_format($transfer->total_paid, 2)); ?></span>
                    </div>
                </div>

                <div style="margin-top: 1.5rem;">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Exchange Rate</span>
                            <span class="info-value">1 <?php echo e($transfer->source_currency); ?> = <?php echo e(number_format($transfer->exchange_rate, 4)); ?> <?php echo e($transfer->target_currency); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Recipient Gets</span>
                            <span class="info-value large"><?php echo e($transfer->target_currency); ?> <?php echo e(number_format($transfer->payout_amount, 2)); ?></span>
                        </div>
                    </div>
                </div>

                <?php if($transfer->promotion): ?>
                    <div style="margin-top: 1rem; padding: 1rem; background: #d1fae5; border-radius: 6px;">
                        <strong style="color: #065f46;">🎉 Promotion Applied:</strong> <?php echo e($transfer->promotion->title); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="receipt-footer">
            <p><strong>Thank you for using SwiftPay!</strong></p>
            <p>For support, contact us at support@swiftpay.com or call 1-800-SWIFT-PAY</p>
            <p style="font-size: 0.875rem; margin-top: 1rem;">This is an official receipt from SwiftPay Money Transfer Services</p>
            <p style="font-size: 0.75rem; color: #9ca3af;">Generated on <?php echo e(now()->format('M d, Y H:i:s')); ?></p>
        </div>
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Receipt</button>
        <a href="<?php echo e(route('transfers.show', $transfer->id)); ?>" class="btn btn-secondary">← Back to Transfer</a>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/transfers/receipt.blade.php ENDPATH**/ ?>