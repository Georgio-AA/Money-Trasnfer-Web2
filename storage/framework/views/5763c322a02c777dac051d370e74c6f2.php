<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>My Transfers</h1>
    <p>View and track all your money transfers in real-time</p>
</section>

<section class="transfers-list-section">
    <div class="container">
        <div class="page-actions">
            <a href="<?php echo e(route('transfers.create')); ?>" class="btn btn-primary">
                <span>+</span> New Transfer
            </a>
            <button class="btn btn-refresh" onclick="location.reload()">
                🔄 Refresh
            </button>
        </div>
        
        <?php if($transfers->isEmpty()): ?>
            <div class="empty-state">
                <div class="empty-icon">📤</div>
                <h3>No transfers yet</h3>
                <p>Start sending money to your beneficiaries</p>
                <a href="<?php echo e(route('transfers.create')); ?>" class="btn btn-primary">Initiate Transfer</a>
            </div>
        <?php else: ?>
            <div class="transfers-table">
                <table>
                    <thead>
                        <tr>
                            <th>Transfer ID</th>
                            <th>Beneficiary</th>
                            <th>Amount</th>
                            <th>Recipient Gets</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>#<?php echo e($transfer->id); ?></td>
                                <td>
                                    <div class="beneficiary-info">
                                        <strong><?php echo e($transfer->beneficiary->full_name); ?></strong>
                                        <small><?php echo e($transfer->beneficiary->country); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="amount-info">
                                        <strong><?php echo e($transfer->source_currency); ?> <?php echo e(number_format($transfer->amount, 2)); ?></strong>
                                        <small>Fee: <?php echo e(number_format($transfer->transfer_fee, 2)); ?></small>
                                    </div>
                                </td>
                                <td class="payout-amount">
                                    <?php echo e($transfer->target_currency); ?> <?php echo e(number_format($transfer->payout_amount, 2)); ?>

                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo e($transfer->status); ?>">
                                        <?php if($transfer->status === 'pending'): ?>
                                            ⏳ Pending
                                        <?php elseif($transfer->status === 'processing'): ?>
                                            ⚙️ Processing
                                        <?php elseif($transfer->status === 'completed'): ?>
                                            ✓ Completed
                                        <?php elseif($transfer->status === 'failed'): ?>
                                            ✗ Failed
                                        <?php elseif($transfer->status === 'refunded'): ?>
                                            ↩ Refunded
                                        <?php else: ?>
                                            <?php echo e(ucfirst($transfer->status)); ?>

                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td><?php echo e($transfer->created_at->format('M d, Y')); ?><br><small class="time"><?php echo e($transfer->created_at->format('h:i A')); ?></small></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo e(route('transfers.show', $transfer->id)); ?>" class="btn-link">
                                            <?php if(in_array($transfer->status, ['pending', 'processing'])): ?>
                                                🔍 Track Status
                                            <?php else: ?>
                                                View Details
                                            <?php endif; ?>
                                        </a>
                                        <?php if($transfer->status === 'completed'): ?>
                                            <a href="<?php echo e(route('transfers.receipt', $transfer->id)); ?>" class="btn-link receipt-link" title="View Receipt">
                                                📄 Receipt
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper">
                <?php echo e($transfers->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.transfers-list-section{padding:2rem 0;min-height:60vh}
.container{max-width:1200px;margin:0 auto;padding:0 1rem}
.page-actions{display:flex;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem}
.btn-primary{background:#2563eb;color:#fff}
.btn-primary:hover{background:#1d4ed8}
.btn-refresh{background:#22c55e;color:#fff}
.btn-refresh:hover{background:#16a34a}
.empty-state{text-align:center;padding:4rem 2rem;background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.empty-icon{font-size:4rem;margin-bottom:1rem}
.empty-state h3{margin:0 0 0.5rem 0;color:#374151;font-size:1.5rem}
.empty-state p{color:#6b7280;margin-bottom:2rem}
.transfers-table{background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);overflow:hidden}
table{width:100%;border-collapse:collapse}
thead{background:#f9fafb;border-bottom:2px solid #e5e7eb}
thead th{padding:1rem;text-align:left;font-weight:600;color:#374151;font-size:0.875rem;text-transform:uppercase}
tbody tr{border-bottom:1px solid #f3f4f6;transition:background 0.2s}
tbody tr:hover{background:#f9fafb}
tbody td{padding:1rem;color:#111827}
.beneficiary-info{display:flex;flex-direction:column;gap:0.25rem}
.beneficiary-info strong{color:#111827}
.beneficiary-info small{color:#6b7280;font-size:0.875rem}
.amount-info{display:flex;flex-direction:column;gap:0.25rem}
.amount-info strong{color:#111827}
.amount-info small{color:#6b7280;font-size:0.875rem}
.payout-amount{color:#059669;font-weight:600}
.time{color:#9ca3af;font-size:0.8rem}
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
.btn-link{color:#2563eb;text-decoration:none;font-weight:600;font-size:0.875rem}
.btn-link:hover{text-decoration:underline}
.btn-link.receipt-link{color:#8b5cf6}
.action-buttons{display:flex;flex-direction:column;gap:0.5rem}
.pagination-wrapper{margin-top:2rem;display:flex;justify-content:center}
@media(max-width:768px){
.transfers-table{overflow-x:auto}
table{min-width:800px}
}
</style>

<script>
// Auto-refresh every 30 seconds if there are pending/processing transfers
<?php if($transfers->whereIn('status', ['pending', 'processing'])->count() > 0): ?>
setInterval(function() {
    location.reload();
}, 30000);
<?php endif; ?>
</script>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/transfers/index.blade.php ENDPATH**/ ?>