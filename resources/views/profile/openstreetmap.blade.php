<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
	<style>
		#map {
			height: 400px;
			width: 100%;
			border-radius: 8px;
			border: 2px solid #e5e7eb;
		}

		.leaflet-container {
			font-family: inherit;
		}

		.coordinates {
			background: white;
			padding: 10px;
			border-radius: 4px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			margin-top: 10px;
		}
	</style>
</head>

<body>
	<div>
		<label class="block text-sm font-medium text-gray-700 mb-2">Pilih Lokasi di Map</label>

		{{-- Input untuk pencarian alamat --}}
		<div class="mb-4">
			<input type="text" id="addressInput" placeholder="Cari alamat..."
				class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-2">
			<button onclick="searchAddress()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
				Cari
			</button>
		</div>

		{{-- Peta --}}
		<div id="map"></div>

		{{-- Koordinat yang dipilih --}}
		<div class="coordinates mt-4">
			<div class="grid grid-cols-2 gap-4">
				<div>
					<label class="text-sm text-gray-600">Latitude</label>
					<input type="text" id="latitude" wire:model="latitude" class="w-full px-3 py-2 border rounded"
						readonly>
				</div>
				<div>
					<label class="text-sm text-gray-600">Longitude</label>
					<input type="text" id="longitude" wire:model="longitude" class="w-full px-3 py-2 border rounded"
						readonly>
				</div>
			</div>

			{{-- Alamat lengkap --}}
			<div class="mt-3">
				<label class="text-sm text-gray-600">Alamat Lengkap</label>
				<textarea id="fullAddress" wire:model="address" class="w-full px-3 py-2 border rounded mt-1" rows="3"
					placeholder="Alamat akan muncul di sini..."></textarea>
			</div>
		</div>

		{{-- Hidden inputs untuk Livewire --}}
		<input type="hidden" wire:model="latitude">
		<input type="hidden" wire:model="longitude">
		<input type="hidden" wire:model="address">
	</div>

	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
	<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
	<script>
		// Inisialisasi peta
        let map;
        let marker;
        let defaultLat = -6.2088; // Jakarta
        let defaultLng = 106.8456;
        
        // Cek apakah ada nilai default dari Livewire
        @if(isset($latitude) && $latitude)
            defaultLat = {{ $latitude }};
        @endif
        
        @if(isset($longitude) && $longitude)
            defaultLng = {{ $longitude }};
        @endif
        
        // Initialize map
        function initMap() {
            map = L.map('map').setView([defaultLat, defaultLng], 13);
            
            // Tambahkan tile layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Tambahkan geocoder (pencarian alamat)
            const geocoder = L.Control.Geocoder.nominatim();
            
            // Buat custom control untuk pencarian
            L.Control.geocoder({
                geocoder: geocoder,
                placeholder: "Cari alamat...",
                errorMessage: "Alamat tidak ditemukan",
                showResultIcons: true,
                collapsed: false,
                position: 'topright'
            }).addTo(map);
            
            // Tambahkan marker awal jika ada koordinat default
            if (defaultLat && defaultLng) {
                marker = L.marker([defaultLat, defaultLng], {
                    draggable: true
                }).addTo(map);
                
                // Update input fields
                updateCoordinates(defaultLat, defaultLng);
                getAddressFromCoords(defaultLat, defaultLng);
            }
            
            // Event klik pada peta
            map.on('click', function(e) {
                const { lat, lng } = e.latlng;
                
                // Hapus marker lama jika ada
                if (marker) {
                    map.removeLayer(marker);
                }
                
                // Buat marker baru
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                
                // Update koordinat
                updateCoordinates(lat, lng);
                getAddressFromCoords(lat, lng);
                
                // Event saat marker dipindahkan
                marker.on('dragend', function(e) {
                    const newPos = marker.getLatLng();
                    updateCoordinates(newPos.lat, newPos.lng);
                    getAddressFromCoords(newPos.lat, newPos.lng);
                });
                
                // Emit ke Livewire
                @this.set('latitude', lat);
                @this.set('longitude', lng);
            });
            
            // Tambahkan tombol lokasi saat ini
            L.control.locate({
                position: 'topright',
                strings: {
                    title: "Lokasi saya"
                }
            }).addTo(map);
        }
        
        // Update koordinat ke input fields
        function updateCoordinates(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        }
        
        // Dapatkan alamat dari koordinat (reverse geocoding)
        function getAddressFromCoords(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        const address = data.display_name;
                        document.getElementById('fullAddress').value = address;
                        @this.set('address', address);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Fungsi pencarian alamat
        function searchAddress() {
            const address = document.getElementById('addressInput').value;
            
            if (!address) return;
            
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const { lat, lon, display_name } = data[0];
                        
                        // Pindahkan peta ke lokasi
                        map.setView([lat, lon], 15);
                        
                        // Hapus marker lama
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        
                        // Buat marker baru
                        marker = L.marker([lat, lon], {
                            draggable: true
                        }).addTo(map);
                        
                        // Update fields
                        updateCoordinates(lat, lon);
                        document.getElementById('fullAddress').value = display_name;
                        
                        // Emit ke Livewire
                        @this.set('latitude', lat);
                        @this.set('longitude', lon);
                        @this.set('address', display_name);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Inisialisasi peta saat halaman load
        document.addEventListener('DOMContentLoaded', initMap);
	</script>
</body>

</html>