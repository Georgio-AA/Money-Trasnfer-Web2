<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

</head><style>
body { background-color: #0e5474ff; }
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
</style>
<body>

    <form action="{{ route('Apply.submit') }}" method="POST">
        @csrf
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
        <input type="number" id="commission_rate" name="commission_rate" required>

        <button type="submit">Register</button>
        </form>

<button onclick="window.location='{{ route('agent.applicationstatus') }}'">View Your Application Status</button>
</body>
</html>
