<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
.fraud-container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
.fraud-header { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2); }
.fraud-header h1 { margin: 0 0 10px 0; font-size: 32px; }
.fraud-header p { margin: 0; opacity: 0.95; font-size: 16px; }

.tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; }
.tab { padding: 12px 24px; background: none; border: none; border-bottom: 3px solid transparent; cursor: pointer; font-size: 16px; font-weight: 500; color: #6b7280; transition: all 0.3s; }
.tab.active { color: #dc2626; border-bottom-color: #dc2626; }
.tab:hover { color: #dc2626; background: #fef2f2; }

.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeIn 0.3s; }

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #dc2626; }
.stat-card h3 { margin: 0 0 10px 0; font-size: 14px; color: #6b7280; text-transform: uppercase; font-weight: 600; }
.stat-card .value { font-size: 32px; font-weight: 700; color: #1f2937; }
.stat-card .subtext { margin-top: 8px; font-size: 13px; color: #9ca3af; }
.stat-card.green { border-left-color: #10b981; }
.stat-card.orange { border-left-color: #f59e0b; }
.stat-card.blue { border-left-color: #3b82f6; }

.alerts-table, .rules-table, .blocked-table { width: 100%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.alerts-table th, .rules-table th, .blocked-table th { background: #f9fafb; padding: 15px; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb; }
.alerts-table td, .rules-table td, .blocked-table td { padding: 15px; border-bottom: 1px solid #f3f4f6; }
.alerts-table tr:hover, .rules-table tr:hover, .blocked-table tr:hover { background: #f9fafb; }

.risk-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
.risk-critical { background: #fee2e2; color: #991b1b; }
.risk-high { background: #fed7aa; color: #9a3412; }
.risk-medium { background: #fef3c7; color: #92400e; }
.risk-low { background: #d1fae5; color: #065f46; }

.status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-reviewed { background: #dbeafe; color: #1e40af; }
.status-confirmed { background: #fee2e2; color: #991b1b; }
.status-false { background: #d1fae5; color: #065f46; }

.fraud-score { font-size: 18px; font-weight: 700; }
.score-critical { color: #dc2626; }
.score-high { color: #ea580c; }
.score-medium { color: #f59e0b; }
.score-low { color: #10b981; }

.btn { padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover { background: #2563eb; }
.btn-success { background: #10b981; color: white; }
.btn-success:hover { background: #059669; }
.btn-danger { background: #dc2626; color: white; }
.btn-danger:hover { background: #b91c1c; }
.btn-warning { background: #f59e0b; color: white; }
.btn-warning:hover { background: #d97706; }
.btn-secondary { background: #6b7280; color: white; }
.btn-secondary:hover { background: #4b5563; }
.btn-sm { padding: 6px 12px; font-size: 13px; }

.action-btns { display: flex; gap: 8px; }

.section-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.section-card h2 { margin: 0 0 20px 0; font-size: 20px; color: #1f2937; display: flex; align-items: center; gap: 10px; }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #374151; }
.form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
.form-group textarea { resize: vertical; min-height: 80px; }

.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
.modal-content { background-color: white; margin: 5% auto; padding: 0; border-radius: 10px; max-width: 600px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
.modal-header { padding: 20px 25px; border-bottom: 1px solid #e5e7eb; }
.modal-header h3 { margin: 0; font-size: 20px; color: #1f2937; }
.modal-body { padding: 25px; }
.modal-footer { padding: 15px 25px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 10px; }

.reasons-list { list-style: none; padding: 0; margin: 10px 0; }
.reasons-list li { padding: 8px 12px; background: #fef2f2; border-left: 3px solid #dc2626; margin-bottom: 8px; font-size: 13px; color: #991b1b; border-radius: 4px; }

.empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
.empty-state-icon { font-size: 48px; margin-bottom: 15px; }

.toggle-switch { position: relative; display: inline-block; width: 50px; height: 24px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
.toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .toggle-slider { background-color: #10b981; }
input:checked + .toggle-slider:before { transform: translateX(26px); }
</style>

<div class="fraud-container">
    <div class="fraud-header">
        <h1>🛡️ Fraud Detection & Prevention</h1>
        <p>Real-time fraud monitoring and automated threat detection system</p>
    </div>

    <?php if(session('success')): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            ✓ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            ⚠ <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Alerts</h3>
            <div class="value"><?php echo e($stats['total_alerts']); ?></div>
            <div class="subtext"><?php echo e($stats['alerts_today']); ?> today</div>
        </div>
        <div class="stat-card orange">
            <h3>High Risk Transfers</h3>
            <div class="value"><?php echo e($stats['high_risk_transfers']); ?></div>
            <div class="subtext">Requiring review</div>
        </div>
        <div class="stat-card green">
            <h3>Blocked Users</h3>
            <div class="value"><?php echo e($stats['blocked_users']); ?></div>
            <div class="subtext"><?php echo e($stats['blocked_ips']); ?> IPs blocked</div>
        </div>
        <div class="stat-card blue">
            <h3>Fraud Prevented</h3>
            <div class="value">$<?php echo e(number_format($stats['fraudulent_amount'], 2)); ?></div>
            <div class="subtext"><?php echo e($stats['prevented_transactions']); ?> transactions (7 days)</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab active" onclick="switchTab('alerts')">🚨 Active Alerts</button>
        <button class="tab" onclick="switchTab('rules')">📋 Fraud Rules</button>
        <button class="tab" onclick="switchTab('blocked')">🚫 Blocked Entities</button>
        <button class="tab" onclick="switchTab('high-risk')">⚠️ High Risk Transfers</button>
    </div>

    <!-- Alerts Tab -->
    <div id="alerts-tab" class="tab-content active">
        <div class="section-card">
            <h2>🚨 Recent Fraud Alerts</h2>
            <?php if(count($alerts) > 0): ?>
                <table class="alerts-table">
                    <thead>
                        <tr>
                            <th>Alert ID</th>
                            <th>User</th>
                            <th>Transfer ID</th>
                            <th>Amount</th>
                            <th>Fraud Score</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><code><?php echo e(substr($alert['id'], 0, 12)); ?></code></td>
                                <td>
                                    <strong><?php echo e($alert['user_name']); ?></strong><br>
                                    <small style="color: #6b7280;"><?php echo e($alert['user_email']); ?></small>
                                </td>
                                <td><a href="<?php echo e(route('admin.transfers.show', $alert['transfer_id'])); ?>" style="color: #3b82f6;">#<?php echo e($alert['transfer_id']); ?></a></td>
                                <td><strong>$<?php echo e(number_format($alert['amount'], 2)); ?></strong></td>
                                <td>
                                    <span class="fraud-score score-<?php echo e($alert['severity']); ?>">
                                        <?php echo e($alert['fraud_score']); ?>%
                                    </span>
                                </td>
                                <td>
                                    <span class="risk-badge risk-<?php echo e($alert['severity']); ?>">
                                        <?php echo e(strtoupper($alert['severity'])); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo e($alert['status'] === 'pending' ? 'pending' : ($alert['status'] === 'confirmed_fraud' ? 'confirmed' : 'false')); ?>">
                                        <?php echo e(str_replace('_', ' ', ucfirst($alert['status']))); ?>

                                    </span>
                                </td>
                                <td><small><?php echo e(\Carbon\Carbon::parse($alert['created_at'])->diffForHumans()); ?></small></td>
                                <td>
                                    <?php if($alert['status'] === 'pending'): ?>
                                        <div class="action-btns">
                                            <button class="btn btn-sm btn-success" onclick="reviewAlert('<?php echo e($alert['id']); ?>', '<?php echo e($alert['transfer_id']); ?>')">Review</button>
                                            <button class="btn btn-sm btn-primary" onclick="viewReasons('<?php echo e(json_encode($alert['reasons'])); ?>')">Details</button>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #9ca3af; font-size: 13px;">
                                            Reviewed by <?php echo e($alert['reviewed_by'] ?? 'Unknown'); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">✅</div>
                    <p>No fraud alerts detected. System is running smoothly!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rules Tab -->
    <div id="rules-tab" class="tab-content">
        <div class="section-card">
            <h2>📋 Fraud Detection Rules</h2>
            <button class="btn btn-primary" onclick="openAddRuleModal()" style="margin-bottom: 20px;">+ Add New Rule</button>
            
            <?php if(count($rules) > 0): ?>
                <table class="rules-table">
                    <thead>
                        <tr>
                            <th>Rule Name</th>
                            <th>Description</th>
                            <th>Condition</th>
                            <th>Score Points</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($rule['name']); ?></strong></td>
                                <td><?php echo e($rule['description']); ?></td>
                                <td><code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px;"><?php echo e($rule['condition']); ?></code></td>
                                <td><span class="fraud-score score-<?php echo e($rule['score_points'] >= 30 ? 'critical' : ($rule['score_points'] >= 20 ? 'high' : 'medium')); ?>">+<?php echo e($rule['score_points']); ?></span></td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" <?php echo e($rule['enabled'] ? 'checked' : ''); ?> onchange="toggleRule('<?php echo e($rule['id']); ?>')">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.fraud.delete-rule', $rule['id'])); ?>" style="display: inline;" onsubmit="return confirm('Delete this rule?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <p>No fraud rules configured yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Blocked Entities Tab -->
    <div id="blocked-tab" class="tab-content">
        <div class="section-card">
            <h2>🚫 Blocked Entities</h2>
            
            <h3 style="margin-top: 30px; color: #dc2626;">Blocked Users</h3>
            <?php if(count($blocked['users']) > 0): ?>
                <table class="blocked-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Reason</th>
                            <th>Blocked By</th>
                            <th>Blocked At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $blocked['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong>#<?php echo e($item['value']); ?></strong></td>
                                <td><?php echo e($item['name'] ?? 'Unknown'); ?></td>
                                <td><?php echo e($item['email'] ?? 'N/A'); ?></td>
                                <td><?php echo e($item['reason']); ?></td>
                                <td><?php echo e($item['blocked_by']); ?></td>
                                <td><small><?php echo e(\Carbon\Carbon::parse($item['blocked_at'])->format('M d, Y H:i')); ?></small></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.fraud.unblock')); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to unblock this user?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="type" value="user">
                                        <input type="hidden" name="value" value="<?php echo e($item['value']); ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">Unblock</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #9ca3af; padding: 20px;">No blocked users</p>
            <?php endif; ?>

            <h3 style="margin-top: 30px; color: #dc2626;">Blocked IP Addresses</h3>
            <?php if(count($blocked['ips']) > 0): ?>
                <table class="blocked-table">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Reason</th>
                            <th>Blocked By</th>
                            <th>Blocked At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $blocked['ips']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><code><?php echo e($item['value']); ?></code></td>
                                <td><?php echo e($item['reason']); ?></td>
                                <td><?php echo e($item['blocked_by']); ?></td>
                                <td><small><?php echo e(\Carbon\Carbon::parse($item['blocked_at'])->format('M d, Y H:i')); ?></small></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.fraud.unblock')); ?>" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="type" value="ip">
                                        <input type="hidden" name="value" value="<?php echo e($item['value']); ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">Unblock</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #9ca3af; padding: 20px;">No blocked IP addresses</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- High Risk Transfers Tab -->
    <div id="high-risk-tab" class="tab-content">
        <div class="section-card">
            <h2>⚠️ High Risk Transfers (Score > 70)</h2>
            <?php if(count($highRiskTransfers) > 0): ?>
                <table class="alerts-table">
                    <thead>
                        <tr>
                            <th>Transfer ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Fraud Score</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $highRiskTransfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong>#<?php echo e($transfer->id); ?></strong></td>
                                <td><?php echo e($transfer->user->name ?? 'Unknown'); ?></td>
                                <td><strong>$<?php echo e(number_format($transfer->amount, 2)); ?></strong></td>
                                <td>
                                    <span class="status-badge status-<?php echo e($transfer->status); ?>">
                                        <?php echo e(ucfirst($transfer->status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="fraud-score score-critical">
                                        <?php echo e($transfer->fraud_score); ?>%
                                    </span>
                                </td>
                                <td><small><?php echo e($transfer->created_at->diffForHumans()); ?></small></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?php echo e(route('admin.transfers.show', $transfer->id)); ?>" class="btn btn-sm btn-primary">View Details</a>
                                        <button class="btn btn-sm btn-warning" onclick="viewReasons('<?php echo e($transfer->fraud_reasons); ?>')">Reasons</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">✅</div>
                    <p>No high-risk transfers at the moment!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Review Alert Modal -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Review Fraud Alert</h3>
        </div>
        <form id="reviewForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Action *</label>
                    <select name="action" required>
                        <option value="">-- Select Action --</option>
                        <option value="approve">Approve (False Positive)</option>
                        <option value="block">Block User & Cancel Transfer</option>
                        <option value="false_positive">Mark as False Positive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Notes (Optional)</label>
                    <textarea name="notes" placeholder="Add any additional notes about this alert..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </div>
        </form>
    </div>
</div>

<!-- Reasons Modal -->
<div id="reasonsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Fraud Detection Reasons</h3>
        </div>
        <div class="modal-body">
            <ul id="reasonsList" class="reasons-list"></ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeReasonsModal()">Close</button>
        </div>
    </div>
</div>

<!-- Add Rule Modal -->
<div id="addRuleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Fraud Rule</h3>
        </div>
        <form method="POST" action="<?php echo e(route('admin.fraud.add-rule')); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Rule Name *</label>
                    <input type="text" name="name" required placeholder="e.g., Suspicious Login Pattern">
                </div>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" required placeholder="Describe what this rule detects..."></textarea>
                </div>
                <div class="form-group">
                    <label>Condition *</label>
                    <input type="text" name="condition" required placeholder="e.g., logins > 5 in 1 hour">
                </div>
                <div class="form-group">
                    <label>Score Points (1-100) *</label>
                    <input type="number" name="score_points" min="1" max="100" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="enabled" value="1" checked>
                        Enable this rule immediately
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddRuleModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Rule</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    event.target.classList.add('active');
    document.getElementById(tabName + '-tab').classList.add('active');
}

function reviewAlert(alertId, transferId) {
    const modal = document.getElementById('reviewModal');
    const form = document.getElementById('reviewForm');
    form.action = '/admin/fraud/review-alert/' + alertId;
    modal.style.display = 'block';
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

function viewReasons(reasonsJson) {
    try {
        const reasons = JSON.parse(reasonsJson);
        const reasonsList = document.getElementById('reasonsList');
        reasonsList.innerHTML = '';
        
        if (Array.isArray(reasons) && reasons.length > 0) {
            reasons.forEach(reason => {
                const li = document.createElement('li');
                li.textContent = reason;
                reasonsList.appendChild(li);
            });
        } else {
            reasonsList.innerHTML = '<li>No specific reasons recorded</li>';
        }
        
        document.getElementById('reasonsModal').style.display = 'block';
    } catch(e) {
        alert('Error displaying reasons');
    }
}

function closeReasonsModal() {
    document.getElementById('reasonsModal').style.display = 'none';
}

function openAddRuleModal() {
    document.getElementById('addRuleModal').style.display = 'block';
}

function closeAddRuleModal() {
    document.getElementById('addRuleModal').style.display = 'none';
}

function toggleRule(ruleId) {
    window.location.href = '/admin/fraud/toggle-rule/' + ruleId;
}

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = ['reviewModal', 'reasonsModal', 'addRuleModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
</script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\money-transfer\resources\views/admin/fraud-detection.blade.php ENDPATH**/ ?>