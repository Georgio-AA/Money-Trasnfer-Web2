<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="form-section">
    <h2>Login to Your Account</h2>

    <?php if(session('error')): ?>
        <p style="color: red;"><?php echo e(session('error')); ?></p>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <p style="color: green;"><?php echo e(session('success')); ?></p>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('login.submit')); ?>">
        <?php echo csrf_field(); ?>
        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
        <p class="note">Don't have an account? <a href="<?php echo e(route('signup')); ?>">Sign up here</a>.</p>
    </form>
</section>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\Users\user\Desktop\Year 4 Sem 7\Web Programming 2\MoneyTransferWP2\WebProject\resources\views/login.blade.php ENDPATH**/ ?>