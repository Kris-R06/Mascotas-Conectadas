@extends('layout.admin')

@section('content')

<!-- Estilos Leaflet para OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <!-- Encabezado idéntico -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-4xl font-extrabold text-blue-950 flex items-center gap-3">
                <i class="ph ph-binoculars text-amber-500"></i> 
                Publicar Avistamiento
            </h1>
            <p class="mt-2 text-slate-500 font-light">
                Reporta una mascota vista en la calle o desorientada para ayudar a encontrar a sus dueños.
            </p>
        </div>
        <a href="{{ route('avistamientos.index') }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-3 px-6 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 whitespace-nowrap">
            <i class="ph ph-arrow-left text-xl"></i> Ver Avistamientos
        </a>
    </div>

    <!-- Contenido del Formulario principal -->
    <div class="w-full max-w-5xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border border-blue-50 p-6 md:p-10">
            
            @if ($errors->any())
                <div class="mb-8 bg-amber-50 border border-amber-200 text-amber-900 p-5 rounded-2xl text-sm space-y-1">
                    <strong class="font-bold flex items-center gap-2 text-amber-950">
                        <i class="ph ph-warning-circle text-lg text-amber-600"></i> Por favor revisa los siguientes campos:
                    </strong>
                    <ul class="list-disc list-inside mt-2 text-xs text-slate-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('avistamientos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() ?? 1 }}">

                <!-- Mascota Vinculada (Opcional) -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600">
                            <i class="ph ph-paw-print text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Mascota Conocida / Vinculada</h2>
                            <p class="text-xs text-slate-400">Si viste a una mascota registrada en la plataforma, selecciónala aquí (Opcional)</p>
                        </div>
                    </div>

                    <div class="bg-amber-50/30 p-4 rounded-2xl border border-amber-100/60 space-y-2">
                        <label for="mascota_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Mascota de la Comunidad <span class="text-slate-400 font-normal">(Opcional)</span>
                        </label>
                        <select name="mascota_id" id="mascota_id" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                            <option value="">-- No sé el nombre / Avistamiento libre --</option>
                            @foreach($mascotas as $mascota)
                                <option value="{{ $mascota->id }}" {{ old('mascota_id') == $mascota->id ? 'selected' : '' }}>
                                    🐾 {{ $mascota->nombre }} — {{ ucfirst($mascota->especie->nombre ?? 'Animal') }} ({{ $mascota->raza ?? 'Mestizo' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sección Ubicación interactiva con OpenStreetMap -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600">
                                <i class="ph ph-map-pin text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-blue-950">Ubicación donde fue visto</h2>
                                <p class="text-xs text-slate-400">Selecciona en el mapa el lugar exacto del avistamiento</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buscador Autocompletado -->
                    <div class="space-y-2 relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Buscar Calle o Colonia
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2 relative">
                            <div class="relative flex-1">
                                <input type="text" id="map-search-input" autocomplete="off" placeholder="Ej. Calle 6, Col. Centro, Matamoros..." class="w-full px-4 py-3 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <ul id="search-suggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-blue-100 rounded-2xl shadow-xl z-50 max-h-60 overflow-y-auto divide-y divide-slate-100 hidden"></ul>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" id="btn-search-map" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-5 py-3 rounded-xl text-xs flex items-center gap-1.5 transition-colors">
                                    <i class="ph ph-magnifying-glass text-base"></i> Buscar
                                </button>
                                <button type="button" id="btn-my-location" title="Obtener mi ubicación actual" class="bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold px-4 py-3 rounded-xl text-xs flex items-center gap-1.5 transition-colors border border-amber-100 whitespace-nowrap">
                                    <i class="ph ph-crosshair text-base"></i> Mi Ubicación
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Mapa OpenStreetMap -->
                    <div id="osm-map" class="w-full h-80 rounded-2xl border border-blue-100 shadow-sm z-0 overflow-hidden relative"></div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                        <div class="space-y-2">
                            <label for="fecha" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Fecha del Avistamiento <span class="text-amber-600">*</span>
                            </label>
                            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="ubicacion_lat" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Ubicación / Colonia <span class="text-amber-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lat" id="ubicacion_lat" value="{{ old('ubicacion_lat') }}" required placeholder="Ej. Col. Jardines, Matamoros" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="ubicacion_lng" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Referencia o Coordenadas <span class="text-amber-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lng" id="ubicacion_lng" value="{{ old('ubicacion_lng') }}" required placeholder="Se actualiza al mover el punto en el mapa" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>
                </div>

                <!-- Sección Imagen y Descripción -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600">
                            <i class="ph ph-image text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Fotografía y Pistas</h2>
                            <p class="text-xs text-slate-400">Sube una foto tomada y describe el estado del animal</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="foto" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Fotografía del Avistamiento
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="w-full bg-amber-50/50 border border-amber-100 p-2.5 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 file:mr-4 file:py-2 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-white hover:file:bg-amber-600 cursor-pointer transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="descripcion" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Descripción / Detalles del Avistamiento <span class="text-amber-600">*</span>
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="4" required placeholder="Describe la hora aproximada, si traía collar, si parecía asustado o lastimado..." class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('avistamientos.index') }}" class="w-full sm:w-auto text-center px-6 py-3 rounded-full text-slate-500 font-bold hover:bg-slate-100 transition-colors text-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 px-8 rounded-full shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="ph ph-binoculars text-xl"></i> Publicar Avistamiento
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- Script de Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var defaultLat = 25.8690;
        var defaultLng = -97.5027;

        var inputLat = document.getElementById('ubicacion_lat');
        var inputLng = document.getElementById('ubicacion_lng');
        var searchInput = document.getElementById('map-search-input');
        var suggestionsList = document.getElementById('search-suggestions');
        var searchBtn = document.getElementById('btn-search-map');

        var map = L.map('osm-map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateInputs(lat, lng, addressName) {
            if (addressName) {
                inputLat.value = addressName;
            }
            inputLng.value = lat.toFixed(6) + ', ' + lng.toFixed(6);
        }

        function reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var displayName = data.display_name || '';
                    var parts = displayName.split(',');
                    var shortAddress = parts.slice(0, 3).join(',').trim();
                    updateInputs(lat, lng, shortAddress);
                });
        }

        marker.on('dragend', function (e) {
            var pos = marker.getLatLng();
            reverseGeocode(pos.lat, pos.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });
    });
</script>

@endsection
