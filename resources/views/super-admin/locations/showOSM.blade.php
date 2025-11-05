@extends('layouts.super-admin')

@section('content')
<div class="container">
    <h4>Location Map</h4>
    <p id="address-text">{{ $location->address ?? '' }}</p>

    <div id="map" style="height: 500px;"></div>
</div>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var lat = {{ $location->lat }};
    var lng = {{ $location->lng }};
    var address = @json($location->address);


    var map = L.map('map').setView([lat, lng], 16);


    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

 
    var marker = L.marker([lat, lng]).addTo(map);

  
    if (!address || address.trim() === "") {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    address = data.display_name;
                    document.getElementById("address-text").innerText = address;
                    marker.bindPopup(address).openPopup();
                }
            })
            .catch(err => console.error("Geocoding error:", err));
    } else {
        marker.bindPopup(address).openPopup();
    }
</script>
@endsection
