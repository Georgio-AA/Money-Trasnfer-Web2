<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Transfer Details</h1>
    <p>Track your money transfer in real-time</p>
</section>

<section class="transfer-details-section">
    <div class="container">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        
        <!-- Status Timeline -->
        <div class="status-timeline">
            <h3>Transfer Status Timeline</h3>
            <div class="timeline">
                <div class="timeline-item <?php echo e(in_array($transfer->status, ['pending', 'processing', 'completed']) ? 'completed' : ''); ?>">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>Transfer Initiated</h4>
                        <p><?php echo e($transfer->created_at->format('M d, Y - h:i A')); ?></p>
                        <small>Your transfer request has been received</small>
                    </div>
                </div>
                
                <div class="timeline-item <?php echo e(in_array($transfer->status, ['processing', 'completed']) ? 'completed' : ($transfer->status === 'pending' ? 'current' : '')); ?>">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>Payment Processing</h4>
                        <?php if(in_array($transfer->status, ['processing', 'completed'])): ?>
                            <p><?php echo e($transfer->updated_at->format('M d, Y - h:i A')); ?></p>
                            <small>Payment is being processed</small>
                        <?php else: ?>
                            <small>Waiting for payment confirmation</small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="timeline-item <?php echo e($transfer->status === 'completed' ? 'completed' : ($transfer->status === 'processing' ? 'current' : '')); ?>">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>Money Sent</h4>
                        <?php if($transfer->status === 'completed'): ?>
                            <p><?php echo e(optional($transfer->completed_at)->format('M d, Y - h:i A') ?? 'Processing'); ?></p>
                            <small>Money is on its way to recipient</small>
                        <?php else: ?>
                            <small>Money will be sent once payment is confirmed</small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="timeline-item <?php echo e($transfer->status === 'completed' ? 'completed' : ''); ?>">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>Transfer Completed</h4>
                        <?php if($transfer->status === 'completed' && $transfer->completed_at): ?>
                            <p><?php echo e($transfer->completed_at->format('M d, Y - h:i A')); ?></p>
                            <small>✓ Recipient has received the money</small>
                        <?php else: ?>
                            <small>Recipient will receive money soon</small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if($transfer->status === 'failed'): ?>
                    <div class="timeline-item failed">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4>Transfer Failed</h4>
                            <p><?php echo e($transfer->updated_at->format('M d, Y - h:i A')); ?></p>
                            <small>⚠ Transfer could not be completed. Please contact support.</small>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if($transfer->status === 'refunded'): ?>
                    <div class="timeline-item refunded">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4>Transfer Refunded</h4>
                            <p><?php echo e($transfer->updated_at->format('M d, Y - h:i A')); ?></p>
                            <small>Money has been refunded to your account</small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="transfer-card">
            <div class="transfer-header">
                <div>
                    <h2>Transfer #<?php echo e($transfer->id); ?></h2>
                    <span class="status-badge status-<?php echo e($transfer->status); ?>"><?php echo e(ucfirst($transfer->status)); ?></span>
                </div>
                <div class="transfer-date">
                    <?php echo e($transfer->created_at->format('M d, Y - h:i A')); ?>

                </div>
            </div>
            
            <div class="transfer-body">
                <div class="detail-section">
                    <h3>Beneficiary Information</h3>
                    <div class="detail-row">
                        <span class="label">Name:</span>
                        <span class="value"><?php echo e($transfer->beneficiary->full_name); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Country:</span>
                        <span class="value"><?php echo e($transfer->beneficiary->country); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Payout Method:</span>
                        <span class="value"><?php echo e(ucwords(str_replace('_', ' ', $transfer->beneficiary->preferred_payout_method ?? 'Bank Deposit'))); ?></span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Transfer Details</h3>
                    <div class="detail-row">
                        <span class="label">Amount Sent:</span>
                        <span class="value"><?php echo e($transfer->source_currency); ?> <?php echo e(number_format($transfer->amount, 2)); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Exchange Rate:</span>
                        <span class="value">1 <?php echo e($transfer->source_currency); ?> = <?php echo e(number_format($transfer->exchange_rate, 4)); ?> <?php echo e($transfer->target_currency); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Transfer Fee:</span>
                        <span class="value"><?php echo e($transfer->source_currency); ?> <?php echo e(number_format($transfer->transfer_fee, 2)); ?></span>
                    </div>
                    <?php if($transfer->promotion): ?>
                        <div class="detail-row promotion">
                            <span class="label">Promotion Applied:</span>
                            <span class="value"><?php echo e($transfer->promotion->title); ?> (-<?php echo e($transfer->promotion->discount_percent); ?>%)</span>
                        </div>
                    <?php endif; ?>
                    <div class="detail-row highlight">
                        <span class="label"><strong>Total Paid:</strong></span>
                        <span class="value"><strong><?php echo e($transfer->source_currency); ?> <?php echo e(number_format($transfer->total_paid, 2)); ?></strong></span>
                    </div>
                    <div class="detail-row payout">
                        <span class="label"><strong>Recipient Receives:</strong></span>
                        <span class="value"><strong><?php echo e($transfer->target_currency); ?> <?php echo e(number_format($transfer->payout_amount, 2)); ?></strong></span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Service Information</h3>
                    <div class="detail-row">
                        <span class="label">Transfer Speed:</span>
                        <span class="value"><?php echo e(ucwords(str_replace('_', ' ', $transfer->transfer_speed))); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Status:</span>
                        <span class="value"><?php echo e(ucfirst($transfer->status)); ?></span>
                    </div>
                    <?php if($transfer->completed_at): ?>
                        <div class="detail-row">
                            <span class="label">Completed At:</span>
                            <span class="value"><?php echo e($transfer->completed_at->format('M d, Y - h:i A')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Admin Status Control (For Testing) -->
            <div class="admin-controls">
                <h3>🔧 Admin Controls (Testing Only)</h3>
                <p>Manually change transfer status to test the money flow:</p>
                <form method="POST" action="<?php echo e(route('transfers.update-status', $transfer->id)); ?>" class="status-form">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="status">Change Status To:</label>
                        <select name="status" id="status" required>
                            <option value="">-- Select Status --</option>
                            <option value="pending" <?php echo e($transfer->status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="processing" <?php echo e($transfer->status === 'processing' ? 'selected' : ''); ?>>Processing</option>
                            <option value="sent" <?php echo e($transfer->status === 'sent' ? 'selected' : ''); ?>>Sent</option>
                            <option value="completed" <?php echo e($transfer->status === 'completed' ? 'selected' : ''); ?>>Completed (Credits Recipient)</option>
                            <option value="failed" <?php echo e($transfer->status === 'failed' ? 'selected' : ''); ?>>Failed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-admin">Update Status</button>
                </form>
                <small class="warning-text">⚠️ When status is set to "Completed", the recipient will automatically receive the money in their account!</small>
            </div>
            
            <div class="transfer-actions">
                <a href="<?php echo e(route('transfers.index')); ?>" class="btn btn-primary">View All Transfers</a>
                <a href="<?php echo e(route('transfers.create')); ?>" class="btn btn-secondary">New Transfer</a>
                <?php if($transfer->status === 'completed'): ?>
                    <a href="<?php echo e(route('transfers.receipt', $transfer->id)); ?>" class="btn btn-receipt">📄 View Receipt</a>
                <?php endif; ?>
                <button class="btn btn-refresh" onclick="location.reload()">🔄 Refresh Status</button>
            </div>
        </div>
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.transfer-details-section{padding:2rem 0;min-height:60vh}
.container{max-width:1000px;margin:0 auto;padding:0 1rem}
.alert{padding:1rem;border-radius:8px;margin-bottom:1.5rem}
.alert-success{background:#f0fdf4;border:1px solid #86efac;color:#166534}

/* Status Timeline Styles */
.status-timeline{background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:2rem;margin-bottom:2rem}
.status-timeline h3{margin:0 0 2rem 0;color:#111827;font-size:1.25rem;text-align:center}
.timeline{position:relative;padding-left:50px}
.timeline::before{content:'';position:absolute;left:20px;top:0;bottom:0;width:3px;background:#e5e7eb}
.timeline-item{position:relative;margin-bottom:2.5rem;padding-bottom:1rem}
.timeline-item:last-child{margin-bottom:0}
.timeline-marker{position:absolute;left:-30px;top:5px;width:20px;height:20px;border-radius:50%;background:#e5e7eb;border:3px solid #fff;box-shadow:0 0 0 3px #e5e7eb;z-index:1}
.timeline-item.completed .timeline-marker{background:#22c55e;box-shadow:0 0 0 3px #dcfce7}
.timeline-item.current .timeline-marker{background:#3b82f6;box-shadow:0 0 0 3px #dbeafe;animation:pulse 2s infinite}
.timeline-item.failed .timeline-marker{background:#ef4444;box-shadow:0 0 0 3px #fee2e2}
.timeline-item.refunded .timeline-marker{background:#8b5cf6;box-shadow:0 0 0 3px #ede9fe}
.timeline-content h4{margin:0 0 0.5rem 0;color:#111827;font-size:1rem}
.timeline-content p{margin:0 0 0.25rem 0;color:#6b7280;font-size:0.875rem}
.timeline-content small{color:#9ca3af;font-size:0.8rem}
.timeline-item.completed .timeline-content h4{color:#059669}
.timeline-item.current .timeline-content h4{color:#2563eb;font-weight:600}
.timeline-item.failed .timeline-content h4{color:#dc2626}
.timeline-item.refunded .timeline-content h4{color:#7c3aed}

@keyframes pulse {
  0%, 100% { box-shadow: 0 0 0 3px #dbeafe; }
  50% { box-shadow: 0 0 0 8px #dbeafe; }
}

.transfer-card{background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);overflow:hidden}
.transfer-header{background:#f9fafb;padding:1.5rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.transfer-header h2{margin:0 0 0.5rem 0;color:#111827}
.transfer-date{color:#6b7280;font-size:0.875rem}
.status-badge{display:inline-block;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.875rem;font-weight:600}
.status-pending{background:#fef3c7;color:#92400e}
.status-processing{background:#dbeafe;color:#1e40af;animation:processingPulse 2s infinite}
.status-completed{background:#d1fae5;color:#065f46}
.status-failed{background:#fee2e2;color:#991b1b}
.status-refunded{background:#e0e7ff;color:#3730a3}

@keyframes processingPulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

.transfer-body{padding:2rem}
.detail-section{margin-bottom:2rem}
.detail-section:last-child{margin-bottom:0}
.detail-section h3{margin:0 0 1rem 0;color:#374151;font-size:1.125rem;border-bottom:2px solid #e5e7eb;padding-bottom:0.5rem}
.detail-row{display:flex;justify-content:space-between;padding:0.75rem 0;border-bottom:1px solid #f3f4f6}
.detail-row:last-child{border-bottom:none}
.detail-row .label{color:#6b7280;font-weight:500}
.detail-row .value{color:#111827;font-weight:600;text-align:right}
.detail-row.highlight{background:#f0fdf4;padding:1rem;border-radius:8px;margin-top:0.5rem}
.detail-row.payout{background:#dcfce7;padding:1rem;border-radius:8px;margin-top:0.5rem}
.detail-row.promotion{background:#fef3c7;padding:0.5rem;border-radius:6px}
.transfer-actions{padding:1.5rem;background:#f9fafb;border-top:1px solid #e5e7eb;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:500;text-decoration:none;display:inline-block}
.btn-primary{background:#2563eb;color:#fff}
.btn-primary:hover{background:#1d4ed8}
.btn-secondary{background:#6b7280;color:#fff}
.btn-secondary:hover{background:#4b5563}
.btn-refresh{background:#22c55e;color:#fff}
.btn-refresh:hover{background:#16a34a}
.btn-receipt{background:#8b5cf6;color:#fff}
.btn-receipt:hover{background:#7c3aed}
.btn-admin{background:#f59e0b;color:#fff}
.btn-admin:hover{background:#d97706}

/* Admin Controls */
.admin-controls{padding:1.5rem;background:#fff7ed;border:2px solid #fed7aa;border-radius:8px;margin:1.5rem 2rem}
.admin-controls h3{margin:0 0 0.5rem 0;color:#c2410c;font-size:1rem}
.admin-controls p{margin:0 0 1rem 0;color:#7c2d12;font-size:0.875rem}
.status-form{display:flex;gap:1rem;align-items:flex-end}
.status-form .form-group{flex:1}
.status-form label{display:block;font-weight:600;margin-bottom:0.5rem;color:#374151;font-size:0.875rem}
.status-form select{width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:8px;font-size:1rem}
.warning-text{display:block;margin-top:0.75rem;color:#dc2626;font-weight:600;font-size:0.875rem}

@media(max-width:768px){
.transfer-header{flex-direction:column;align-items:flex-start}
.detail-row{flex-direction:column;gap:0.25rem}
.detail-row .value{text-align:left}
.transfer-actions{flex-direction:column}
.timeline{padding-left:40px}
}
</style>

<script>
// Auto-refresh every 30 seconds if transfer is not completed
<?php if(in_array($transfer->status, ['pending', 'processing'])): ?>
setInterval(function() {
    location.reload();
}, 30000);
<?php endif; ?>
</script>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\Users\user\Desktop\Year 4 Sem 7\Web Programming 2\MoneyTransferWP2\WebProject\resources\views/transfers/show.blade.php ENDPATH**/ ?>