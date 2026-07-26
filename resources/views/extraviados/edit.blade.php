@extends('layout.admin')

@section('content')

<!-- Estilos Leaflet para OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <!-- Encabezado idéntico al Index y Create -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-4xl font-extrabold text-blue-950 flex items-center gap-3">
                <i class="ph ph-pencil-simple text-blue-500"></i> 
                Editar Alerta de Extravío
            </h1>
            <p class="mt-2 text-slate-500 font-light">
                Modifica los datos, ubicación o fotografía del reporte de extravío.
            </p>
        </div>
        <a href="{{ route('extraviados.index') }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-3 px-6 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 whitespace-nowrap">
            <i class="ph ph-arrow-left text-xl"></i> Regresar a Extravíos
        </a>
    </div>

    <!-- Contenido del Formulario principal -->
    <div class="w-full max-w-5xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border border-blue-50 p-6 md:p-10">
            
            @if ($errors->any())
                <div class="mb-8 bg-blue-50/80 border border-blue-200 text-blue-900 p-5 rounded-2xl text-sm space-y-1">
                    <strong class="font-bold flex items-center gap-2 text-blue-950">
                        <i class="ph ph-warning-circle text-lg text-blue-600"></i> Por favor revisa los siguientes campos:
                    </strong>
                    <ul class="list-disc list-inside mt-2 text-xs text-slate-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('extraviados.update', $reporte->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Sección 1: Selección de Mascota -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                            <i class="ph ph-paw-print text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Información de la Mascota</h2>
                            <p class="text-xs text-slate-400">Mascota vinculada al reporte de extravío</p>
                        </div>
                    </div>

                    <div class="bg-blue-50/30 p-4 sm:p-5 rounded-2xl border border-blue-100/60 space-y-3">
                        <label for="mascota_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Mascota Registrada <span class="text-blue-600">*</span>
                        </label>
                        <select name="mascota_id" id="mascota_id" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                            @foreach($mascotas as $mascota)
                                @php
                                    $fotoUrl = $mascota->foto ? (Str::startsWith($mascota->foto, 'http') ? $mascota->foto : asset('storage/' . $mascota->foto)) : 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=300&q=80';
                                @endphp
                                <option value="{{ $mascota->id }}" data-nombre="{{ $mascota->nombre }}" data-foto="{{ $fotoUrl }}" {{ old('mascota_id', $reporte->mascota_id) == $mascota->id ? 'selected' : '' }}>
                                    🐾 {{ $mascota->nombre }} — {{ ucfirst($mascota->especie->nombre ?? 'Animal') }} ({{ $mascota->raza ?? 'Mestizo' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sección 2: Ubicación interactiva con OpenStreetMap -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                                <i class="ph ph-map-pin text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-blue-950">Ubicación de Desaparición</h2>
                                <p class="text-xs text-slate-400">Actualiza la ubicación en el mapa</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buscador Autocompletado en Tiempo Real -->
                    <div class="space-y-2 relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Buscar Calle o Colonia
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2 relative">
                            <div class="relative flex-1">
                                <input type="text" id="map-search-input" autocomplete="off" placeholder="Escribe para buscar ubicación..." class="w-full px-4 py-3 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <ul id="search-suggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-blue-100 rounded-2xl shadow-xl z-50 max-h-60 overflow-y-auto divide-y divide-slate-100 hidden"></ul>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" id="btn-search-map" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-xl text-xs flex items-center gap-1.5 transition-colors">
                                    <i class="ph ph-magnifying-glass text-base"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor del Mapa OpenStreetMap (Leaflet) -->
                    <div id="osm-map" class="w-full h-80 rounded-2xl border border-blue-100 shadow-sm z-0 overflow-hidden relative"></div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                        <!-- Fecha -->
                        <div class="space-y-2">
                            <label for="fecha" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Fecha de Extravío <span class="text-blue-600">*</span>
                            </label>
                            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', optional($reporte->fecha)->format('Y-m-d')) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>

                        <!-- Colonia / Ubicación -->
                        <div class="space-y-2">
                            <label for="ubicacion_lat" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Ubicación / Colonia <span class="text-blue-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lat" id="ubicacion_lat" value="{{ old('ubicacion_lat', $reporte->ubicacion_lat) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>

                        <!-- Referencia / Coordenadas -->
                        <div class="space-y-2">
                            <label for="ubicacion_lng" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Referencia o Coordenadas <span class="text-blue-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lng" id="ubicacion_lng" value="{{ old('ubicacion_lng', $reporte->ubicacion_lng) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Fotografías y Descripción -->
                <div class="space-y-6 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                            <i class="ph ph-image text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Fotografías y Descripción</h2>
                            <p class="text-xs text-slate-400">Actualiza las señas particulares o la imagen del reporte</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Foto Actual -->
                        <div class="bg-blue-50/40 p-4 sm:p-5 rounded-2xl border border-blue-100/80 space-y-3">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Foto Actual del Reporte
                            </label>
                            <div class="flex items-center gap-4 bg-white p-3 rounded-xl border border-blue-100 shadow-sm">
                                <div class="relative w-20 h-20 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                    @php
                                        $fotoReporte = $reporte->foto ? (Str::startsWith($reporte->foto, 'http') ? $reporte->foto : asset('storage/' . $reporte->foto)) : asset('storage/mascotas/default.jpg');
                                    @endphp
                                    <img src="{{ $fotoReporte }}" alt="Foto Reporte" class="w-full h-full object-cover">
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-xs font-bold text-slate-800">Fotografía guardada</h4>
                                    <p class="text-[11px] text-slate-500">Se mantendrá esta imagen a menos que subas un archivo nuevo.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subir Nueva Foto -->
                        <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3">
                            <label for="foto" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Cambiar Fotografía <span class="text-slate-400 font-normal">(Opcional)</span>
                            </label>
                            <input type="file" name="foto" id="foto" accept="image/*" class="w-full bg-white border border-slate-200 p-2.5 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition-all">
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="space-y-2">
                        <label for="descripcion" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Descripción / Señas Particulares <span class="text-blue-600">*</span>
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="4" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">{{ old('descripcion', $reporte->descripcion) }}</textarea>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('extraviados.index') }}" class="w-full sm:w-auto text-center px-6 py-3 rounded-full text-slate-500 font-bold hover:bg-slate-100 transition-colors text-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-full shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="ph ph-check-circle text-xl"></i> Guardar Cambios del Reporte
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- Script de Leaflet y Autocompletado Nominatim API para OpenStreetMap -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var defaultLat = 25.8690;
        var defaultLng = -97.5027;

        var rawLng = "{{ $reporte->ubicacion_lng }}";
        if (rawLng && rawLng.includes(',')) {
            var parts = rawLng.split(',');
            var pLat = parseFloat(parts[0].trim());
            var pLng = parseFloat(parts[1].trim());
            if (!isNaN(pLat) && !isNaN(pLng)) {
                defaultLat = pLat;
                defaultLng = pLng;
            }
        }

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

        // Autocompletado predictivo en vivo al escribir la calle
        if (searchInput && suggestionsList) {
            var debounceTimer = null;

            function hideSuggestions() {
                suggestionsList.classList.add('hidden');
                suggestionsList.innerHTML = '';
            }

            searchInput.addEventListener('input', function () {
                var query = searchInput.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    hideSuggestions();
                    return;
                }

                debounceTimer = setTimeout(function () {
                    var fullQuery = query;
                    if (!fullQuery.toLowerCase().includes('matamoros')) {
                        fullQuery += ', Matamoros, Tamaulipas, Mexico';
                    }

                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(fullQuery)}&countrycodes=mx&limit=6&addressdetails=1&accept-language=es`)
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            suggestionsList.innerHTML = '';
                            if (!data || data.length === 0) {
                                hideSuggestions();
                                return;
                            }

                            data.forEach(function (item) {
                                var li = document.createElement('li');
                                li.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-center gap-2.5 text-xs text-slate-700 transition-colors border-b border-slate-100 last:border-0';
                                
                                var shortTitle = item.display_name.split(',').slice(0, 3).join(',').trim();
                                li.innerHTML = `<i class="ph ph-map-pin text-blue-500 text-base flex-shrink-0"></i> <div><strong class="block text-slate-800 font-bold">${shortTitle}</strong><span class="text-[10px] text-slate-400 block">${item.display_name}</span></div>`;

                                li.addEventListener('click', function () {
                                    var lat = parseFloat(item.lat);
                                    var lon = parseFloat(item.lon);
                                    searchInput.value = shortTitle;
                                    hideSuggestions();

                                    map.setView([lat, lon], 16);
                                    marker.setLatLng([lat, lon]);
                                    updateInputs(lat, lon, shortTitle);
                                });

                                suggestionsList.appendChild(li);
                            });

                            suggestionsList.classList.remove('hidden');
                        })
                        .catch(function () { hideSuggestions(); });
                }, 300);
            });

            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                    hideSuggestions();
                }
            });
        }

        if (searchBtn && searchInput) {
            searchBtn.addEventListener('click', function () {
                var query = searchInput.value.trim();
                if (!query) return;
                if (!query.toLowerCase().includes('matamoros')) {
                    query += ', Matamoros, Tamaulipas, Mexico';
                }
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=mx`)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (data && data.length > 0) {
                            var lat = parseFloat(data[0].lat);
                            var lon = parseFloat(data[0].lon);
                            map.setView([lat, lon], 15);
                            marker.setLatLng([lat, lon]);
                            updateInputs(lat, lon, data[0].display_name.split(',').slice(0, 3).join(',').trim());
                        }
                    });
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
