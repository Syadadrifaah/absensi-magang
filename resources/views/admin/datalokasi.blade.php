@extends('layouts.index')

@section('judul', 'Data Lokasi Absensi')

@section('content')

<style>
/* Styling untuk modal agar konsisten */
.modal-content {
    border-radius: 10px;
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 12px 20px;
}

/* Styling untuk map */
.leaflet-container {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Styling untuk circle radius */
.leaflet-interactive {
    stroke-width: 2;
}
</style>

<div class="container">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- BUTTON TAMBAH --}}
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalLokasi">
        + Tambah Lokasi
    </button>

    {{-- DATA LOKASI --}}
    <div class="row">
        @forelse ($lokasis as $lokasi)
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5>{{ $lokasi->nama_lokasi }}</h5>
                    <small>Lat: {{ $lokasi->latitude }}</small><br>
                    <small>Lng: {{ $lokasi->longitude }}</small><br>
                    <small>Radius: {{ $lokasi->radius }} m</small><br>

                    <span class="badge {{ $lokasi->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $lokasi->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>

                    <div class="mt-3 d-flex gap-1">
                        {{-- EDIT --}}
                        <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editLokasi{{ $lokasi->id }}">
                            ✏️ Edit
                        </button>

                        {{-- HAPUS --}}
                        <form action="{{ route('lokasi.destroy', $lokasi->id) }}" method="POST"
                              onsubmit="return confirm('Yakin hapus lokasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada data lokasi.</p>
        @endforelse
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div class="modal fade" id="modalLokasi" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('lokasi.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lokasi Absensi</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama Lokasi</label>
                        <input type="text" name="nama_lokasi" class="form-control form-control-sm" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                            <label>Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-6 mb-2">
                            <label>Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control form-control-sm" readonly>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label>Radius (meter)</label>
                        <input type="number" name="radius" id="radius" class="form-control form-control-sm" value="100">
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-success mb-2" onclick="getMyLocation()">
                        📍 Ambil Lokasi Saya
                    </button>

                    <div id="map" style="height:250px;border-radius:8px;"></div>
                </div>

                <div class="modal-footer py-2">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
@foreach ($lokasis as $lokasi)
<div class="modal fade" id="editLokasi{{ $lokasi->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{ route('lokasi.update', $lokasi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Lokasi Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama Lokasi</label>
                        <input type="text" name="nama_lokasi" 
                               class="form-control form-control-sm" 
                               value="{{ $lokasi->nama_lokasi }}" 
                               required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                            <label>Latitude</label>
                            <input type="text" name="latitude" 
                                   id="editLatitude{{ $lokasi->id }}" 
                                   class="form-control form-control-sm" 
                                   value="{{ $lokasi->latitude }}" 
                                   readonly>
                        </div>
                        <div class="col-6 mb-2">
                            <label>Longitude</label>
                            <input type="text" name="longitude" 
                                   id="editLongitude{{ $lokasi->id }}" 
                                   class="form-control form-control-sm" 
                                   value="{{ $lokasi->longitude }}" 
                                   readonly>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label>Radius (meter)</label>
                        <input type="number" name="radius" 
                               id="editRadius{{ $lokasi->id }}" 
                               class="form-control form-control-sm" 
                               value="{{ $lokasi->radius }}">
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="is_active" class="form-select form-select-sm">
                            <option value="1" {{ $lokasi->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$lokasi->is_active ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-success mb-2" 
                            onclick="getMyLocationEdit({{ $lokasi->id }})">
                        📍 Ambil Lokasi Saya
                    </button>

                    <div id="editMap{{ $lokasi->id }}" style="height:250px;border-radius:8px;"></div>
                </div>

                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- LEAFLET --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // ================= MODAL TAMBAH =================
    let map, marker, circle;

    document.getElementById('modalLokasi').addEventListener('shown.bs.modal', () => {
        if (!map) initMap();
        setTimeout(() => map.invalidateSize(), 200);
    });

    function initMap() {
        const lat = -5.147665;
        const lng = 119.432732;

        map = L.map('map').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        circle = L.circle([lat, lng], { radius: 100 }).addTo(map);

        updateForm(lat, lng);

        marker.on('dragend', () => {
            const pos = marker.getLatLng();
            updateForm(pos.lat, pos.lng);
        });
    }

    function updateForm(lat, lng) {
        latitude.value = lat.toFixed(6);
        longitude.value = lng.toFixed(6);
        marker.setLatLng([lat, lng]);
        circle.setLatLng([lat, lng]);
    }

    radius.addEventListener('input', () => {
        if (circle) circle.setRadius(radius.value);
    });

    function getMyLocation() {
        navigator.geolocation.getCurrentPosition(pos => {
            updateForm(pos.coords.latitude, pos.coords.longitude);
            map.setView([pos.coords.latitude, pos.coords.longitude], 17);
        }, () => alert('Izin lokasi ditolak'));
    }

 
// ================= VARIABLES UNTUK EDIT =================
let editMaps = {};
let editMarkers = {};
let editCircles = {};

// ================= INITIALIZE EDIT MAP =================
function initEditMap(lokasiId) {
    const latInput = document.getElementById('editLatitude' + lokasiId);
    const lngInput = document.getElementById('editLongitude' + lokasiId);
    const radiusInput = document.getElementById('editRadius' + lokasiId);
    const mapContainer = document.getElementById('editMap' + lokasiId);
    
    if (!latInput || !lngInput || !radiusInput || !mapContainer) return;
    
    const lat = parseFloat(latInput.value) || -5.147665;
    const lng = parseFloat(lngInput.value) || 119.432732;
    const radius = parseFloat(radiusInput.value) || 100;
    
    // Initialize map
    const map = L.map(mapContainer).setView([lat, lng], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
    // Add marker
    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    
    // Add circle
    const circle = L.circle([lat, lng], {
        radius: radius,
        color: '#0d6efd',
        fillColor: '#0d6efd',
        fillOpacity: 0.2
    }).addTo(map);
    
    // Update form when marker is dragged
    marker.on('dragend', function() {
        const pos = marker.getLatLng();
        latInput.value = pos.lat.toFixed(6);
        lngInput.value = pos.lng.toFixed(6);
        circle.setLatLng([pos.lat, pos.lng]);
    });
    
    // Update circle radius when input changes
    radiusInput.addEventListener('input', function() {
        const newRadius = parseFloat(this.value) || 100;
        circle.setRadius(newRadius);
    });
    
    // Save references
    editMaps[lokasiId] = map;
    editMarkers[lokasiId] = marker;
    editCircles[lokasiId] = circle;
    
    // Fix map size after modal opens
    setTimeout(() => map.invalidateSize(), 100);
}

// ================= EVENT LISTENERS FOR EDIT MODALS =================
@foreach ($lokasis as $lokasi)
document.getElementById('editLokasi{{ $lokasi->id }}').addEventListener('shown.bs.modal', function() {
    initEditMap({{ $lokasi->id }});
});

document.getElementById('editLokasi{{ $lokasi->id }}').addEventListener('hidden.bs.modal', function() {
    // Cleanup map when modal is closed
    if (editMaps[{{ $lokasi->id }}]) {
        editMaps[{{ $lokasi->id }}].remove();
        delete editMaps[{{ $lokasi->id }}];
        delete editMarkers[{{ $lokasi->id }}];
        delete editCircles[{{ $lokasi->id }}];
    }
});
@endforeach

// ================= GET CURRENT LOCATION FOR EDIT =================
function getMyLocationEdit(lokasiId) {
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung geolocation');
        return;
    }
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Update inputs
            document.getElementById('editLatitude' + lokasiId).value = lat.toFixed(6);
            document.getElementById('editLongitude' + lokasiId).value = lng.toFixed(6);
            
            // Update map if exists
            if (editMarkers[lokasiId] && editCircles[lokasiId] && editMaps[lokasiId]) {
                editMarkers[lokasiId].setLatLng([lat, lng]);
                editCircles[lokasiId].setLatLng([lat, lng]);
                editMaps[lokasiId].setView([lat, lng], 17);
            }
        },
        function(error) {
            let message = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message = 'Izin lokasi ditolak. Silakan aktifkan lokasi di browser.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = 'Informasi lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    message = 'Permintaan lokasi timeout.';
                    break;
                default:
                    message = 'Error tidak diketahui.';
            }
            alert(message);
        }
    );
}

</script>
@endsection
