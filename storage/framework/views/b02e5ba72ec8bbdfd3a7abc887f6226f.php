<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="signup-container">
    <div class="signup-card">

        <h2 class="title">Create Your SwiftPay Account</h2>

        <?php if($errors->any()): ?>
            <div class="error-box">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li style="color:red"><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('signup.submit')); ?>" class="signup-form">
            <?php echo csrf_field(); ?>

            <div class="input-group">
                <label>First Name</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required>
            </div>

            <div class="input-group">
                <label>Surname</label>
                <input type="text" name="surname" value="<?php echo e(old('surname')); ?>" required>
            </div>

            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required>
            </div>

            <div class="input-group">
                <label>Age</label>
                <input type="number" name="age" value="<?php echo e(old('age')); ?>" required>
            </div>

            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="submit-btn">Create Account</button>

            <p class="login-note">
                Already have an account?
                <a href="<?php echo e(route('login')); ?>">Login</a>
            </p>
        </form>

        <div class="divider">or</div>

        <div class="social-login-wrapper">
            <a href="<?php echo e(route('google.login')); ?>" class="social-btn google-btn">
                <i class="fab fa-google"></i> Sign up with Google
            </a>
            <a href="<?php echo e(route('facebook.login')); ?>" class="social-btn facebook-btn">
                <i class="fab fa-facebook"></i> Sign up with Facebook
            </a>
        </div>

    </div>
</section>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>
/* Page Layout */
.signup-container {
    display: flex;
    justify-content: center;
    padding: 40px 15px;
    background: #f4f7fb;
    min-height: 90vh;
}

.signup-card {
    background: white;
    padding: 35px 40px;
    width: 420px;
    border-radius: 12px;
    box-shadow: 0px 6px 20px rgba(0,0,0,0.08);
}

/* Title */
.title {
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 25px;
}

/* Form Inputs */
.signup-form .input-group {
    margin-bottom: 18px;
}

.signup-form label {
    font-size: 14px;
    font-weight: bold;
    color: #34495e;
    display: block;
    margin-bottom: 6px;
}

.signup-form input {
    width: 100%;
    padding: 11px;
    border-radius: 6px;
    border: 1px solid #cfd6df;
    font-size: 14px;
    background: #fafbff;
}

.signup-form input:focus {
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

/* Login Link */
.login-note {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.login-note a {
    color: #4a8df6;
    font-weight: bold;
    text-decoration: none;
}

/* Social Login */
.divider {
    text-align: center;
    margin: 25px 0 18px;
    color: #95a5a6;
    font-size: 14px;
}

.social-login-wrapper {
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: center;
}

.social-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 260px;
    padding: 12px;
    border-radius: 6px;
    font-weight: bold;
    font-size: 15px;
    color: white;
    text-decoration: none;
    transition: 0.2s;
}

.google-btn {
    background: #db4437;
}
.google-btn:hover {
    background: #b33428;
}

.facebook-btn {
    background: #1877F2;
}
.facebook-btn:hover {
    background: #0f59c2;
}

.social-btn i {
    margin-right: 8px;
}
</style>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\Users\user\Desktop\Year 4 Sem 7\Web Programming 2\MoneyTransferWP2\WebProject\resources\views/signup.blade.php ENDPATH**/ ?>