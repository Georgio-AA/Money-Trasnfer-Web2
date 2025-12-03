@include('includes.header')

<style>
body {
    background: #f3f4f6;
    font-family: Inter, sans-serif;
}

.profile-card {
    max-width: 700px;
    margin: 40px auto;
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.profile-card h2 {
    font-size: 22px;
    margin-bottom: 16px;
    color: #1a202c;
}

.profile-card label {
    display: block;
    margin: 8px 0 4px;
    font-weight: 600;
    color: #4a5568;
}

.profile-card input[type="text"] {
    width: 100%;
    padding: 8px 10px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    margin-bottom: 12px;
    font-size: 14px;
}

.profile-card button {
    background: #2563eb;
    color: white;
    font-weight: 600;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: 0.15s ease;
}

.profile-card button:hover {
    background: #1d4ed8;
}

#map {
    height: 350px;
    width: 100%;
    margin-bottom: 16px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}
</style>

<div class="profile-card">
    <h2>Manage Profile & Location</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('agent.profile.update') }}" method="POST">
        @csrf
        <label for="working_hours">Working Hours</label>
        <input type="text" name="working_hours" id="working_hours" value="{{ $agent->working_hours }}">

        <label>Location</label>
        <div id="map"></div>

        <input type="hidden" name="latitude" id="latitude" value="{{ $agent->latitude }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ $agent->longitude }}">

        <button type="submit">Update Profile</button>
    </form>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([{{ $agent->latitude }}, {{ $agent->longitude }}], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

let marker = L.marker([{{ $agent->latitude }}, {{ $agent->longitude }}], {draggable: true}).addTo(map);

marker.on('dragend', function(e) {
    const pos = marker.getLatLng();
    document.getElementById('latitude').value = pos.lat;
    document.getElementById('longitude').value = pos.lng;
});
</script>
