<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="form-section">
    <h2>Create Your SwiftPay Account</h2>

    <?php if($errors->any()): ?>
        <div class="error">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="color:red"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('signup.submit')); ?>">
        <?php echo csrf_field(); ?>

        <label for="name">First Name:</label>
        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required>

        <label for="surname">Surname:</label>
        <input type="text" name="surname" id="surname" value="<?php echo e(old('surname')); ?>" required>

         <label for="surname">Phone Number:</label>
        <input type="text" name="phone" id="phone" value="<?php echo e(old('phone')); ?>" required>

        <label for="age">Age:</label>
        <input type="number" name="age" id="age" value="<?php echo e(old('age')); ?>" required>

        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>

        <button type="submit">Sign Up</button>
        <p class="note">Already have an account? <a href="<?php echo e(route('login')); ?>">Login</a>.</p>
    </form>
</section>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/signup.blade.php ENDPATH**/ ?>