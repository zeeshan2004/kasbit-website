@extends('layout.app')

@section('content')

@include('frontend.partials.header')

<!-- LOCATION HERO -->
<section class="py-5" style="background: linear-gradient(135deg, #0d47a1 0%, #0a2540 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold text-white mb-3">
                    {{ $location['name'] }}
                </h1>
                <p class="lead text-light">
                    {{ $location['address'] }}
                </p>
            </div>
            <div class="col-lg-4">
                <div class="text-white">
                    <div class="mb-3">
                        <i class="fa-solid fa-map-location-dot fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LOCATION DETAILS -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="card-title mb-3">About This Location</h3>
                        <p class="text-muted">
                            KASBIT's {{ $location['name'] }} campus provides state-of-the-art facilities for students and faculty. 
                            Located in {{ $location['address'] }}, this campus is equipped with modern classrooms, laboratories, 
                            and recreational facilities to support an excellent learning environment.
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Location Map</h3>
                        <div id="map" style="width: 100%; height: 400px; border-radius: 8px; margin-bottom: 20px;"></div>
                        <a href="#" target="_blank" class="btn btn-outline-primary btn-lg w-100" id="mapsLink">
                            <i class="fa-solid fa-map-location-dot me-2"></i> Open in Google Maps
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Facilities</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Modern Classrooms</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Computer Labs</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Library</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Cafeteria</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Parking</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Prayer Rooms</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Contact Information</h5>
                        
                        @if($home->header_phone ?? false)
                        <div class="mb-3">
                            <i class="fa-solid fa-phone text-primary me-2"></i>
                            <a href="tel:{{ str_replace(' ', '', $home->header_phone) }}" class="text-decoration-none text-dark">
                                {{ $home->header_phone }}
                            </a>
                        </div>
                        @endif

                        @if($home->header_email ?? false)
                        <div class="mb-3">
                            <i class="fa-solid fa-envelope text-primary me-2"></i>
                            <a href="mailto:{{ $home->header_email }}" class="text-decoration-none text-dark">
                                {{ $home->header_email }}
                            </a>
                        </div>
                        @endif

                        <div class="mb-3">
                            <i class="fa-solid fa-map-location-dot text-primary me-2"></i>
                            <span>{{ $location['address'] }}, Karachi</span>
                        </div>

                        <hr>

                        <a href="/" class="btn btn-primary w-100 mb-2">
                            <i class="fa-solid fa-house me-2"></i> Back to Home
                        </a>
                        
                        <button class="btn btn-outline-primary w-100">
                            <i class="fa-solid fa-calendar me-2"></i> Book Visit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')

@endsection

@push('scripts')
    <script>
        (() => {
            const locationsData = {
                1: { name: 'SMCHS', lat: 24.8607, lng: 67.0011, address: 'SMCHS, Karachi' },
                2: { name: 'Hyderi', lat: 24.9056, lng: 67.0644, address: 'Hyderi, Karachi' },
                3: { name: 'Gulshan', lat: 24.9250, lng: 67.1620, address: 'Gulshan-e-Iqbal, Karachi' }
            };
            const currentLocation = locationsData[{{ (int) $id }}];
            const mapsLink = document.getElementById('mapsLink');

            if (mapsLink) {
                mapsLink.href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(currentLocation.address)}`;
            }

            window.initKasbitMap = function () {
                const mapElement = document.getElementById('map');

                if (!mapElement || !window.google?.maps) return;

                const position = {
                    lat: currentLocation.lat,
                    lng: currentLocation.lng
                };
                const map = new google.maps.Map(mapElement, {
                    zoom: 15,
                    center: position,
                    mapTypeControl: false,
                    streetViewControl: false
                });

                new google.maps.Marker({
                    position,
                    map,
                    title: currentLocation.name
                });
            };
        })();
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3S8MwLz5qKj-qfq3TKz-5iy0QhYJJvfw&loading=async&callback=initKasbitMap"></script>
@endpush
