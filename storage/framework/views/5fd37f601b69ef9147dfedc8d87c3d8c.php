<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="page-header">
    <h1>Find Transfer Services</h1>
    <p>Filter by country, fees, speed, payout method, and offers.</p>
    </section>

<section class="filters">
    <div class="container">
        <form method="GET" action="<?php echo e(route('transfer-services.index')); ?>" class="filter-form" onsubmit="return true;">
            <div class="grid">
                <div class="field">
                    <label>Destination Country</label>
                    <select name="destination_country" id="dest-country">
                        <option value="">Any</option>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($code); ?>" <?php echo e($filters['destination_country']===$code ? 'selected' : ''); ?>><?php echo e($name); ?> (<?php echo e($code); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small class="hint">Pick where the money will be received.</small>
                </div>
                <div class="field">
                    <label>Transfer fees and exchange rates</label>
                    <input type="text" name="fees_rates" value="<?php echo e($filters['fees_rates'] ?? ''); ?>" placeholder="e.g. 2.5% or 'cheap/best rate' or 'low fees'">
                    <small class="hint">Enter a percent (like 2.5%) or words like 'cheap', 'best rate'.</small>
                </div>
                <div class="field">
                    <label>Transfer Speed</label>
                    <select name="speed">
                        <option value="">Any</option>
                        <?php $__currentLoopData = $speeds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php echo e($filters['speed']===$k ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="field">
                    <label>Payout Method</label>
                    <select name="payout_method">
                        <option value="">Any</option>
                        <?php $__currentLoopData = $payoutMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php echo e($filters['payout_method']===$k ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="field checkbox">
                    <label>
                        <input type="checkbox" name="offers" value="1" <?php echo e($filters['offers'] ? 'checked' : ''); ?>> Offers & Promotions
                    </label>
                </div>
            </div>
            <div class="actions">
                <button class="btn btn-primary" type="submit">Search</button>
                <a class="btn btn-secondary" href="<?php echo e(route('transfer-services.index')); ?>">Clear</a>
            </div>
        </form>

    </div>
</section>

<section class="results">
    <div class="container">
        <?php if($services->count()): ?>
            <div class="cards">
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo e($svc->name); ?></h3>
                            <?php if($svc->has_promotions): ?>
                                <span class="badge promo">Promo</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div><strong>Speed:</strong> <?php echo e(str_replace('_',' ',ucfirst($svc->transfer_speed))); ?></div>
                            <div><strong>Fees:</strong> <?php echo e(rtrim(rtrim(number_format($svc->fee_percent,2), '0'),'.')); ?>% + <?php echo e(number_format($svc->fee_fixed,2)); ?></div>
                            <div><strong>Payout:</strong> <?php echo e(implode(', ', array_map('ucwords', str_replace('_',' ', $svc->payout_methods ?? [])))); ?></div>
                            <div><strong>FX Margin:</strong> <?php echo e(number_format($svc->fx_margin_percent ?? 0,2)); ?>%</div>
                            <div class="meta">Supports: <?php echo e(implode(', ', $svc->destination_countries ?? [])); ?></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty">No exact matches. Try broadening your search or check these suggested services:</div>
            <?php if(isset($suggested) && $suggested->count()): ?>
                <div class="cards">
                    <?php $__currentLoopData = $suggested; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card">
                            <div class="card-header">
                                <h3><?php echo e($svc->name); ?></h3>
                                <?php if($svc->has_promotions): ?>
                                    <span class="badge promo">Promo</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <div><strong>Speed:</strong> <?php echo e(str_replace('_',' ',ucfirst($svc->transfer_speed))); ?></div>
                                <div><strong>Fees:</strong> <?php echo e(rtrim(rtrim(number_format($svc->fee_percent,2), '0'),'.')); ?>% + <?php echo e(number_format($svc->fee_fixed,2)); ?></div>
                                <div><strong>Payout:</strong> <?php echo e(implode(', ', array_map('ucwords', str_replace('_',' ', $svc->payout_methods ?? [])))); ?></div>
                                <div><strong>FX Margin:</strong> <?php echo e(number_format($svc->fx_margin_percent ?? 0,2)); ?>%</div>
                                <div class="meta">Supports: <?php echo e(implode(', ', $svc->destination_countries ?? [])); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.filters,.results{padding:1.25rem}.container{max-width:1100px;margin:0 auto}
.filter-form .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;margin-bottom:12px}
.field label{display:block;font-weight:600;margin-bottom:6px}
.field input,.field select{width:100%;padding:.5rem;border:1px solid #e5e7eb;border-radius:6px}
.hint{color:#6b7280;font-size:.8rem}
.actions{margin-top:.5rem;display:flex;gap:.5rem}
.presets{display:none}
.field.checkbox{display:flex;align-items:end}
.btn{padding:.6rem 1rem;border:none;border-radius:8px;cursor:pointer}
.btn-primary{background:#2563eb;color:#fff}
.btn-secondary{background:#6b7280;color:#fff}
.cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}
.card{border:1px solid #e5e7eb;border-radius:10px;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.06)}
.card-header{display:flex;justify-content:space-between;align-items:center;padding:.9rem 1rem;border-bottom:1px solid #f3f4f6}
.card-body{padding:1rem;color:#374151}
.estimate{display:none}
.badge.promo{background:#fde68a;color:#92400e;border-radius:999px;padding:.2rem .6rem;font-size:.8rem}
.midrate{display:none}
.empty{padding:2rem;text-align:center;color:#6b7280}
</style>

<script></script>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/transfer-services/index.blade.php ENDPATH**/ ?>