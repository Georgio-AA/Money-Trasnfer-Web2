<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="login-container">
    <div class="login-card">

        <h2 class="title">Login to Your Account</h2>

        <?php if(session('error')): ?>
            <div class="alert error"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login.submit')); ?>" class="login-form">
            <?php echo csrf_field(); ?>

            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="submit-btn">Login</button>

            <p class="signup-note">
                Don't have an account?
                <a href="<?php echo e(route('signup')); ?>">Sign up here</a>.
            </p>
        </form>

    </div>
</section>


<style>
/* Page Layout */
.login-container {
    display: flex;
    justify-content: center;
    padding: 40px 15px;
    background: #f4f7fb;
height: auto;}

.login-card {
    background: #ffffff;
    padding: 35px 40px;
    width: 420px;
    border-radius: 12px;
    box-shadow: 0px 6px 20px rgba(0,0,0,0.08);
    height: auto;
}

/* Title */
.title {
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 25px;
}

/* Alerts */
.alert {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 14px;
    font-weight: bold;
    text-align: center;
}

.alert.error {
    background: #ffe4e4;
    color: #d63939;
    border: 1px solid #ffcccc;
}

.alert.success {
    background: #e5f9e7;
    color: #2f8d4e;
    border: 1px solid #bff3c7;
}

/* Form Inputs */
.login-form .input-group {
    margin-bottom: 18px;
}

.login-form label {
    font-size: 14px;
    font-weight: bold;
    color: #34495e;
    display: block;
    margin-bottom: 6px;
}

.login-form input {
    width: 100%;
    padding: 11px;
    border-radius: 6px;
    border: 1px solid #cfd6df;
    background: #fafbff;
    font-size: 14px;
}

.login-form input:focus {
    border-color: #4a90e2;
    outline: none;
    box-shadow: 0 0 4px rgba(74,144,226,0.3);
}

/* Submit Button */
.submit-btn {
    width: 100%;
    background: #4a8df6;
    padding: 12px;
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
    margin-top: 10px;
}

.submit-btn:hover {
    background: #2f6fe0;
}

/* Signup Text */
.signup-note {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.signup-note a {
    color: #4a8df6;
    font-weight: bold;
    text-decoration: none;
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/login.blade.php ENDPATH**/ ?>