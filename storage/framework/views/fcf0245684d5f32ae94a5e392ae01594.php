<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

</head>
<link rel="stylesheet" href="<?php echo e(asset('css/agent.css')); ?>">


<body>

    <form action="<?php echo e(route('Apply.submit')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <h2>Register</h2>

        <label for="store_name">Store Name</label>
        <input type="text" id="store_name" name="store_name" required>

        
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
    
        <label for="city">City</label>
        <input type="text" id="city" name="city" required>
        
        <label for="phone_number">Phone Number</label>
        <input  type="text" id="phone_number" name="phone_number" required>

        <label for="latitude">Latitude</label>
        <input type="number" id="latitude" name="latitude" step="any" required>

        <label for="longitude">Longitude</label>
        <input  type="number" id="longitude" name="longitude" step="any" required>

        <label for="working_hours">Working Hours</label>
        <input type="text" id="working_hours" name="working_hours" required>

        <label for="commission_rate">Commission Rate</label>
        <input type="number" id="commission_rate" name="commission_rate" step="0.01" min="0" required>

        <button type="submit">Register</button>
        </form>

<button onclick="window.location='<?php echo e(route('agent.applicationstatus')); ?>'">View Your Application Status</button>
<button onclick="window.location='<?php echo e(route('home')); ?>'">Back to Home</button>
</body>
</html>
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="form-wrapper">

    <form action="<?php echo e(route('Apply.submit')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <h2>Register as Agent</h2>

        <label>Store Name</label>
        <input type="text" name="store_name" required>

        <label>Address</label>
        <input type="text" name="address" required>

        <label>Country</label>
        <select name="country" required>
            <option value="">-- Select --</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Syria">Syria</option>
            <option value="USA">USA</option>
            <option value="Canada">Canada</option>
        </select>

        <label>City</label>
        <input type="text" name="city" required>

        <label>Phone Number</label>
        <input type="text" name="phone_number" required>

        <label>Latitude</label>
        <input type="number" step="any" name="latitude" required>

        <label>Longitude</label>
        <input type="number" step="any" name="longitude" required>

        <label>Working Hours</label>
        <input type="text" name="working_hours" required>

        <label>Commission Rate</label>
        <input type="number" step="0.01" min="0" name="commission_rate" required>

        <button type="submit">Register</button>
    </form>

    <div class="extra-buttons">
        <button onclick="window.location='<?php echo e(route('agent.applicationstatus')); ?>'">
            View Application Status
        </button>

        <button onclick="window.location='<?php echo e(route('home')); ?>'">
            Back to Home
        </button>
    </div>

</div>

</body>
</html>

<style>:root{
  --primary:#2563eb;
  --page-bg: linear-gradient(180deg,#0e5474 0%, #063b52 100%);
}

html,body{
  height:100%;
  margin:0;
  font-family: system-ui, "Segoe UI", Roboto, Arial;
  background: var(--page-bg);
  color:#0f172a;
}

/* center the form on screen */
.form-wrapper {
  max-width: 400px;
  margin: 40px auto;
  padding: 0 16px;
}

/* card */
form {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.25);
}

form h2 {
  text-align:center;
  margin-bottom: 16px;
  font-size: 20px;
}

/* labels + inputs aligned perfectly */
form label {
  font-size: 14px;
  font-weight: 600;
  margin: 8px 0 4px;
  display:block;
}

form input,
form select {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  margin-bottom: 10px;
  font-size: 14px;
  box-sizing: border-box;
}

form input:focus,
form select:focus {
  outline:none;
  border-color: var(--primary);
  box-shadow: 0 0 8px rgba(37,99,235,0.3);
}

/* submit button */
form button {
  width: 100%;
  padding: 10px;
  background: var(--primary);
  color:#fff;
  border:none;
  border-radius: 8px;
  font-size: 15px;
  font-weight:600;
  margin-top: 10px;
  cursor:pointer;
}

form button:hover {
  opacity:0.95;
}

/* other buttons below card */
.extra-buttons {
  margin-top: 14px;
  text-align:center;
}

.extra-buttons button {
  width:100%;
  padding:10px;
  margin-bottom: 8px;
  border-radius:8px;
  background: transparent;
  color:#fff;
  border:1px solid rgba(255,255,255,0.4);
  cursor:pointer;
  font-size:14px;
}

.extra-buttons button:hover {
  background: rgba(255,255,255,0.1);
}
</style><?php /**PATH C:\XAMPP\htdocs\money-transfer2\WebProject\resources\views/agent/applytobeagent.blade.php ENDPATH**/ ?>