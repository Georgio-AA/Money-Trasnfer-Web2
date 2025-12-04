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
        <div class="logo">
            <ul>
                 <li><a href="<?php echo e(route('home')); ?>">SwiftPay</a></li>
            </ul>
                           

        </div>
        
        <nav>
            <ul>
                <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                <li><a href="<?php echo e(route('services')); ?>">Services</a></li>
                <?php if(session('user.role') !== 'agent'): ?>
                <li><a href="<?php echo e(route('agent.applytobeagent')); ?>">Become an Agent</a></li>
                <?php endif; ?>
                <?php if(session('user.role') === 'agent'): ?>
                <li><a href="<?php echo e(route('agent.dashboard')); ?>">Agent Dashboard</a></li>
                <li><a href="<?php echo e(route('agent.profile.edit')); ?>">Agent Profile</a></li>
                <?php endif; ?>

                <?php if(session()->has('user')): ?>
                    <li><a href="<?php echo e(route('wallet.index')); ?>" class="wallet-link">💰 My Wallet</a></li>
                    <li><a href="<?php echo e(route('transfers.create')); ?>" class="send-money-link">Send Money</a></li>
                    <li><a href="<?php echo e(route('store.index')); ?>" class="store-link">🛍️ Store</a></li>
                    <li><a href="<?php echo e(route('transfers.index')); ?>">My Transfers</a></li>
                    <li><a href="<?php echo e(route('beneficiaries.index')); ?>">Beneficiaries</a></li>
                    <li><a href="<?php echo e(route('bank-accounts.index')); ?>">My Accounts</a></li>
                    <li><a href="<?php echo e(route('support.index')); ?>">Support</a></li>
                    <li><a href="<?php echo e(route('reviews.index')); ?>">Reviews</a></li>
                    <li><a href="<?php echo e(route('disputes.index')); ?>">Disputes</a></li>
                    <?php if(session('user.role') === 'admin'): ?>
                        <li><a href="<?php echo e(route('admin.dashboard')); ?>">Admin Panel</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo e(route('transfer-services.index')); ?>">Transfer Services</a></li>
                <?php else: ?>
                    <li><a href="<?php echo e(route('login')); ?>">Send Money</a></li>
                <?php endif; ?>

                <?php if(session()->has('user')): ?>
                   
                    <li><a href="<?php echo e(route('profile')); ?>" style="color: inherit; text-decoration: none;">Welcome, <?php echo e(session('user.name')); ?></a></li>
                    <li>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
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

    <style>
        .logout-btn {
    background: #db4646;
    color: white;
    padding: 10px 8px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.logout-btn:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}
    </style>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/includes/header.blade.php ENDPATH**/ ?>