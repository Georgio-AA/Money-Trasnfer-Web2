<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>My Wallet</h1>
    <p>View your balance and transaction history</p>
</section>

<section class="wallet-section">
    <div class="container">
        <!-- Balance Card -->
        <div class="balance-display-card">
            <div class="balance-main">
                <div class="balance-icon-large">💰</div>
                <div class="balance-details">
                    <span class="balance-label">Available Balance</span>
                    <span class="balance-amount-large"><?php echo e($freshUser->currency); ?> <?php echo e(number_format($freshUser->balance, 2)); ?></span>
                    <small class="balance-hint">Updated: <?php echo e(now()->format('M d, Y - h:i A')); ?></small>
                </div>
            </div>
            <div class="balance-actions">
                <a href="<?php echo e(route('transfers.create')); ?>" class="btn btn-send">
                    <span>📤</span> Send Money
                </a>
                <a href="<?php echo e(route('wallet.deposit.form')); ?>" class="btn btn-deposit">
                    <span>💵</span> Add Money
                </a>
                <a href="<?php echo e(route('wallet.withdraw.form')); ?>" class="btn btn-withdraw">
                    <span>💸</span> Withdraw
                </a>
                <button class="btn btn-refresh" onclick="location.reload()">
                    <span>🔄</span> Refresh
                </button>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="transactions-card">
            <h2>Transaction History</h2>
            
            <?php if($transactions->isEmpty()): ?>
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <p>No transactions yet</p>
                    <small>Your transaction history will appear here</small>
                </div>
            <?php else: ?>
                <div class="transactions-list">
                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="transaction-item transaction-<?php echo e($transaction->transaction_type); ?>">
                            <div class="transaction-icon">
                                <?php if($transaction->transaction_type === 'credit'): ?>
                                    <span class="icon-credit">↓</span>
                                <?php else: ?>
                                    <span class="icon-debit">↑</span>
                                <?php endif; ?>
                            </div>
                            <div class="transaction-info">
                                <div class="transaction-title">
                                    <?php if($transaction->transaction_type === 'credit'): ?>
                                        <strong>Money Received</strong>
                                    <?php else: ?>
                                        <strong>Money Sent</strong>
                                    <?php endif; ?>
                                    <span class="transaction-status status-<?php echo e($transaction->status); ?>">
                                        <?php echo e(ucfirst($transaction->status)); ?>

                                    </span>
                                </div>
                                <div class="transaction-description"><?php echo e($transaction->description); ?></div>
                                <div class="transaction-date"><?php echo e($transaction->created_at->format('M d, Y - h:i A')); ?></div>
                            </div>
                            <div class="transaction-amount transaction-<?php echo e($transaction->transaction_type); ?>">
                                <?php if($transaction->transaction_type === 'credit'): ?>
                                    <span class="amount-credit">+<?php echo e($transaction->currency); ?> <?php echo e(number_format($transaction->amount, 2)); ?></span>
                                <?php else: ?>
                                    <span class="amount-debit">-<?php echo e($transaction->currency); ?> <?php echo e(number_format($transaction->amount, 2)); ?></span>
                                <?php endif; ?>
                                <?php if($transaction->transfer_id): ?>
                                    <a href="<?php echo e(route('transfers.show', $transaction->transfer_id)); ?>" class="view-link">View Transfer</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    <?php echo e($transactions->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;padding:2rem;text-align:center}
.wallet-section{padding:2rem 0;min-height:70vh;background:#f9fafb}
.container{max-width:1000px;margin:0 auto;padding:0 1rem}

/* Balance Display Card */
.balance-display-card{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;padding:2rem;border-radius:16px;box-shadow:0 8px 24px rgba(79,70,229,0.4);margin-bottom:2rem}
.balance-main{display:flex;align-items:center;gap:1.5rem;margin-bottom:1.5rem}
.balance-icon-large{font-size:4rem}
.balance-details{flex:1}
.balance-label{display:block;font-size:1rem;opacity:0.9;margin-bottom:0.5rem}
.balance-amount-large{display:block;font-size:2.5rem;font-weight:700;letter-spacing:-0.02em}
.balance-hint{display:block;margin-top:0.5rem;opacity:0.8;font-size:0.875rem}
.balance-actions{display:flex;gap:1rem}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:10px;cursor:pointer;font-size:1rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;transition:all 0.2s}
.btn-send{background:#22c55e;color:#fff}
.btn-send:hover{background:#16a34a;transform:translateY(-2px);box-shadow:0 4px 12px rgba(34,197,94,0.4)}
.btn-deposit{background:#3b82f6;color:#fff}
.btn-deposit:hover{background:#2563eb;transform:translateY(-2px);box-shadow:0 4px 12px rgba(59,130,246,0.4)}
.btn-withdraw{background:#f59e0b;color:#fff}
.btn-withdraw:hover{background:#d97706;transform:translateY(-2px);box-shadow:0 4px 12px rgba(245,158,11,0.4)}
.btn-refresh{background:rgba(255,255,255,0.2);color:#fff;border:2px solid rgba(255,255,255,0.3)}
.btn-refresh:hover{background:rgba(255,255,255,0.3)}

/* Transactions Card */
.transactions-card{background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);padding:2rem}
.transactions-card h2{margin:0 0 1.5rem 0;color:#111827;font-size:1.5rem;padding-bottom:1rem;border-bottom:2px solid #e5e7eb}

/* Empty State */
.empty-state{text-align:center;padding:3rem 1rem;color:#6b7280}
.empty-icon{font-size:4rem;margin-bottom:1rem;opacity:0.5}
.empty-state p{font-size:1.125rem;font-weight:600;margin:0 0 0.5rem 0}
.empty-state small{font-size:0.875rem}

/* Transaction List */
.transactions-list{display:flex;flex-direction:column;gap:0.5rem}
.transaction-item{display:flex;align-items:center;gap:1rem;padding:1rem;border-radius:10px;border:1px solid #e5e7eb;transition:all 0.2s}
.transaction-item:hover{border-color:#d1d5db;box-shadow:0 2px 8px rgba(0,0,0,0.06)}
.transaction-icon{width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0}
.transaction-credit .transaction-icon{background:#dcfce7}
.transaction-debit .transaction-icon{background:#fee2e2}
.icon-credit{color:#16a34a;font-weight:700}
.icon-debit{color:#dc2626;font-weight:700}
.transaction-info{flex:1;min-width:0}
.transaction-title{display:flex;align-items:center;gap:0.5rem;margin-bottom:0.25rem;flex-wrap:wrap}
.transaction-title strong{color:#111827;font-size:1rem}
.transaction-status{font-size:0.75rem;padding:0.25rem 0.5rem;border-radius:999px;font-weight:600}
.status-completed{background:#d1fae5;color:#065f46}
.status-pending{background:#fef3c7;color:#92400e}
.status-failed{background:#fee2e2;color:#991b1b}
.transaction-description{color:#6b7280;font-size:0.875rem;margin-bottom:0.25rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.transaction-date{color:#9ca3af;font-size:0.75rem}
.transaction-amount{text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:0.25rem}
.amount-credit{font-size:1.25rem;font-weight:700;color:#16a34a}
.amount-debit{font-size:1.25rem;font-weight:700;color:#dc2626}
.view-link{font-size:0.75rem;color:#6366f1;text-decoration:none;font-weight:500}
.view-link:hover{text-decoration:underline}

/* Pagination */
.pagination-wrapper{margin-top:2rem;padding-top:1.5rem;border-top:1px solid #e5e7eb}

@media(max-width:768px){
.balance-main{flex-direction:column;text-align:center}
.balance-actions{flex-direction:column;width:100%}
.balance-actions .btn{width:100%}
.transaction-item{flex-direction:column;align-items:flex-start;gap:0.75rem}
.transaction-amount{align-items:flex-start;text-align:left}
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/wallet/index.blade.php ENDPATH**/ ?>