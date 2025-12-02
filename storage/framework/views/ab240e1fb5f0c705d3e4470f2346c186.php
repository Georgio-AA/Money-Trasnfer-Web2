<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftPay - Money Transfer</title>
    <?php echo app('Illuminate\Foundation\Vite')('assets/css/style.css'); ?>
</head>
<body>
<header>
    <div class="navbar">
        <div class="logo">SwiftPay</div>
        <nav>
            <ul>
                <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                <li><a href="<?php echo e(route('services')); ?>">Services</a></li>
                <li><a href="<?php echo e(route('agent.applytobeagent')); ?>">Become an Agent</a></li>
                <?php if(session('user.role') === 'agent'): ?>
                <li><a href="<?php echo e(route('agent.welcome')); ?>">Agent Dashboard</a></li>
                <?php endif; ?>

                <?php if(session()->has('user')): ?>
                    <li><a href="<?php echo e(route('wallet.index')); ?>" class="wallet-link">💰 My Wallet</a></li>
                    <li><a href="<?php echo e(route('transfers.create')); ?>" class="send-money-link">Send Money</a></li>
                    <li><a href="<?php echo e(route('transfers.index')); ?>">My Transfers</a></li>
                    <li><a href="<?php echo e(route('beneficiaries.index')); ?>">Beneficiaries</a></li>
                    <li><a href="<?php echo e(route('bank-accounts.index')); ?>">My Accounts</a></li>
                    <li><a href="<?php echo e(route('support.index')); ?>">Support</a></li>
                    <li><a href="<?php echo e(route('reviews.index')); ?>">Reviews</a></li>
                    <li><a href="<?php echo e(route('disputes.index')); ?>">Disputes</a></li>
                    <?php if(session('user.role') === 'admin'): ?>
                        <li><a href="<?php echo e(route('admin.dashboard')); ?>" style="color: #f59e0b; font-weight: 600;">Admin Panel</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo e(route('transfer-services.index')); ?>">Transfer Services</a></li>
                <?php else: ?>
                    <li><a href="<?php echo e(route('login')); ?>">Send Money</a></li>
                <?php endif; ?>

                <?php if(session()->has('user')): ?>
                    
                    <?php if(session('user.is_admin')): ?>
                        <li><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo e(route('profile')); ?>" style="color: inherit; text-decoration: none;">Welcome, <?php echo e(session('user.name')); ?></a></li>
                    <li>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </li>
                <?php else: ?>
                    
                    <li><a href="<?php echo e(route('login')); ?>">Login</a></li>
                    <li><a href="<?php echo e(route('signup')); ?>" class="signup-btn">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<main>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/includes/header.blade.php ENDPATH**/ ?>