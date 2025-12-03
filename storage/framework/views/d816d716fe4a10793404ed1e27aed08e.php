<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="edit-profile-container" style="max-width: 600px; margin: 40px auto; padding: 20px; background: #f9f9f9; border-radius: 8px;">
    <h1 style="color: #333; margin-bottom: 30px;">Edit Profile</h1>
    
    <?php if($errors->any()): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="<?php echo e(route('profile.update')); ?>" method="POST" style="background: white; padding: 20px; border-radius: 5px;">
        <?php echo csrf_field(); ?>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; color: #333; margin-bottom: 8px;">Name</label>
            <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; color: #333; margin-bottom: 8px;">Surname</label>
            <input type="text" name="surname" value="<?php echo e(old('surname', $user->surname)); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; color: #333; margin-bottom: 8px;">Age</label>
            <input type="number" name="age" value="<?php echo e(old('age', $user->age)); ?>" required min="1" max="120" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; color: #333; margin-bottom: 8px;">Email</label>
            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; color: #333; margin-bottom: 8px;">Phone</label>
            <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px;">Save Changes</button>
            <a href="<?php echo e(route('profile')); ?>" style="display: inline-block; background: #999; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancel</a>
        </div>
    </form>
</div>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/edit-profile.blade.php ENDPATH**/ ?>