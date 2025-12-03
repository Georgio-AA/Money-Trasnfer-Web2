<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

</head>
<style>
:root{
  --primary:#2563eb;
  --accent:#06b6d4;
  --muted:#6b7280;
  --radius:10px;
  --card-bg:#ffffff;
  --page-bg: linear-gradient(180deg,#0e5474 0%, #063b52 100%);
}

html,body{
  height:100%;
  margin:0;
  font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  background: var(--page-bg);
  color:#0f172a;
}

/* Centered card container */
body > form, body > .signup-card, body > .signup-container {
  display:block;
  max-width:700px; /* smaller than before */
  margin:16px auto;
  padding:0 12px;
}

/* Card visual */
form {
  background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(250,250,255,0.95));
  border-radius: var(--radius);
  padding:16px; /* reduced padding */
  box-shadow: 0 6px 18px rgba(2,6,23,0.35);
  border: 1px solid rgba(255,255,255,0.06);
}

/* Headline */
form h2 {
  margin:0 0 10px 0; /* less bottom margin */
  font-size:18px;
  font-weight:700;
  text-align:center;
}

/* Labels & inputs */
form label {
  display:block;
  font-size:12px; /* slightly smaller */
  font-weight:600;
  color: #374151;
  margin:4px 0 2px; /* closer to input */
}

form input[type="text"],
form input[type="number"],
form select {
  max-width:200px; /* shrink horizontally */
  margin:0 auto;
  display:block;
  width:100%;
  padding:6px 10px; /* smaller height */
  border-radius:8px;
  border:1px solid #e5e7eb;
  background:#ffffff;
  font-size:13px;
  color:#0f172a;
  transition: box-shadow .15s ease, border-color .15s ease;
}

form input:focus,
form select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 4px 10px rgba(37,99,235,0.08);
}

/* Two-column layout */
.two-col {
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:10px;
  margin-bottom:6px;
}

/* Buttons */
form button[type="submit"],
body > button {
  width:100%;
  padding:8px 12px; /* smaller buttons */
  border-radius:8px;
  font-weight:700;
  font-size:13px;
  cursor:pointer;
  border:none;
  color:#fff;
  background: linear-gradient(90deg,var(--primary), #1d4ed8);
  box-shadow: 0 5px 16px rgba(37,99,235,0.15);
  margin-top:10px;
  transition: transform .06s ease, box-shadow .06s ease, opacity .06s ease;
}

form button[type="submit"]:hover,
body > button:hover{
  transform: translateY(-1px);
  box-shadow: 0 8px 20px rgba(37,99,235,0.18);
  opacity:0.98;
}

body > button {
  margin-top:8px;
  background: transparent;
  color:#fff;
  border: 1px solid rgba(255,255,255,0.12);
  max-width:260px;
}

/* Error text */
span.error {
  display:block;
  color:#dc2626;
  font-size:12px;
  margin-top:2px;
}

/* Responsive tweaks */
@media (max-width:900px){
  .two-col {
    grid-template-columns:1fr;
  }
  form { padding:14px; }
  form h2 { font-size:16px; }
  form button[type="submit"] { font-size:13px; padding:8px; }
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
        <input type="number" id="commission_rate" name="commission_rate" step="0.01" min="0" required>

        <button type="submit">Register</button>
        </form>

<button onclick="window.location='{{ route('agent.applicationstatus') }}'">View Your Application Status</button>

</body>
</html>
