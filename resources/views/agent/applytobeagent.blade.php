<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

</head>
<link rel="stylesheet" href="{{ asset('css/agent.css') }}">


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
<button onclick="window.location='{{ route('home') }}'">Back to Home</button>
</body>
</html>
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Become an Agent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="page">
  <div class="header">
    <div class="brand">SwiftPay</div>
    <div class="subtitle">Agent Application</div>
  </div>

  <div class="card">
    <form action="{{ route('Apply.submit') }}" method="POST">
      @csrf
      <div class="title">Register as Agent</div>

      <div class="grid">
        <label>
          <span>Store Name</span>
          <input type="text" name="store_name" placeholder="OMT, Western Union..." required>
        </label>

        <label>
          <span>Address</span>
          <input type="text" name="address" placeholder="Street, Building" required>
        </label>

        <label>
          <span>Country</span>
          <select name="country" required>
            <option value="">Select country</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Syria">Syria</option>
            <option value="USA">USA</option>
            <option value="Canada">Canada</option>
          </select>
        </label>

        <label>
          <span>City</span>
          <input type="text" name="city" placeholder="Beirut" required>
        </label>

        <label>
          <span>Phone Number</span>
          <input type="text" name="phone_number" placeholder="e.g. +961 70 123 456" required>
        </label>

        <label>
          <span>Working Hours</span>
          <input type="text" name="working_hours" placeholder="Mon–Fri 9:00–17:00" required>
        </label>

        <label>
          <span>Commission Rate</span>
          <input type="number" step="0.01" min="0" name="commission_rate" placeholder="e.g. 2.5" required>
        </label>
      </div>

      <div class="hint">Coordinates are auto-filled from your address.</div>

      <button type="submit" class="primary">Submit Application</button>
    </form>
  </div>

  <div class="actions">
    <button class="ghost" onclick="window.location='{{ route('agent.applicationstatus') }}'">View Application Status</button>
    <button class="ghost" onclick="window.location='{{ route('home') }}'">Back to Home</button>
  </div>
</div>

</body>
</html>

<style>
:root{
  --bg:#f6f9fc;
  --card:#ffffff;
  --ink:#0f172a;
  --muted:#64748b;
  --accent:#00c853; /* cash-app green */
}

html,body{height:100%;margin:0;font-family: system-ui, "Segoe UI", Roboto, Arial; background:var(--bg) !important; color:var(--ink)}

.page{max-width:760px;margin:32px auto;padding:0 16px}
.header{display:flex;align-items:baseline;gap:10px;margin-bottom:12px}
.brand{font-weight:800;letter-spacing:0.2px}
.subtitle{color:var(--muted)}

.card{background:var(--card);border:1px solid #e2e8f0;border-radius:16px;padding:18px;box-shadow:0 10px 24px rgba(15,23,42,0.06)}
.title{font-weight:700;margin-bottom:8px}

.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
.grid label{display:flex;flex-direction:column;gap:6px}
.grid span{font-size:12px;color:var(--muted)}
input,select{background:#f8fafc;color:var(--ink);border:1px solid #cbd5e1;border-radius:10px;padding:10px 12px;outline:none}
input::placeholder{color:#94a3b8}
select{appearance:none}
input:focus,select:focus{border-color:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.15)}

.hint{margin:10px 0 2px;color:#64748b;font-size:12px}

.primary{width:100%;margin-top:10px;background:var(--accent);color:#053314;border:none;border-radius:12px;padding:12px 14px;font-weight:700;cursor:pointer}
.primary:hover{filter:brightness(1.05)}

.actions{display:flex;gap:10px;margin-top:12px}
.ghost{flex:1;background:#ffffff;color:#0f172a;border:1px solid #cbd5e1;border-radius:12px;padding:10px;cursor:pointer}
.ghost:hover{background:#f1f5f9}

@media (max-width:640px){
  .grid{grid-template-columns:1fr}
}
</style>