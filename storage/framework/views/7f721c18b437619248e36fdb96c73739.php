<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('FormStyle.css')); ?>" >
</head>
<body>

    <form action="<?php echo e(route('Apply.submit')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <h2>Register</h2>

        <label for="storename">Store Name</label>
        <input type="text" id="storename" name="storename" required>

        
        <label for="address">Address</label>
        <input type="text" id="address" name="address" required>

        <label for="country">Country</label>
        <select id="country" name="country" required>
            <option value="">-- Select --</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Syria">Syria</option>
            <option value="USA">USA</option>
            <option value="Canada">Canada</option>
        </select>
        
        <label for="latitude">Latitude</label>
        <input type="number" id="latitude" name="latitude" required>

        <label for="longitude">Longitude</label>
        <input  type="number" id="longitude" name="longitude" required>

        <label for="working_hours">Working Hours</label>
        <input type="number" id="working_hours" name="working_hours" required>

        <label for="commission_rate">Commission Rate</label>
        <input type="number" id="commission_rate" name="commission_rate" required>

        <button type="submit">Register</button>
        </form>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/agent/applytobeagent.blade.php ENDPATH**/ ?>