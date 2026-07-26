@extends('layout.admin')

@section('content')

<!-- Estilos Leaflet para OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <!-- Encabezado -->
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
        <a href="{{ route('avistamientos.index') }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-3 px-6 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 whitespace-nowrap text-sm">
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

                <!-- Sección 1: Selección de Mascota / Datos Básicos -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600">
                            <i class="ph ph-paw-print text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Información del Avistamiento</h2>
                            <p class="text-xs text-slate-400">Indica si es una mascota vista en la calle o de un reporte registrado</p>
                        </div>
                    </div>

                    <div class="bg-amber-50/30 p-5 rounded-2xl border border-amber-100/60 space-y-4">
                        <!-- Selector simple de tipo de reporte -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label id="card-type-unknown" class="flex items-center gap-3 p-4 rounded-xl border-2 border-amber-500 bg-white cursor-pointer transition-all shadow-sm">
                                <input type="radio" name="tipo_avistamiento" value="desconocida" checked class="accent-amber-500 w-4 h-4">
                                <div class="text-sm">
                                    <strong class="font-bold text-slate-800 block">Mascota sin identificar (En la calle)</strong>
                                    <span class="text-xs text-slate-500">Un animal visto en la calle de nombre desconocido.</span>
                                </div>
                            </label>

                            <label id="card-type-known" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-white hover:border-amber-300 cursor-pointer transition-all">
                                <input type="radio" name="tipo_avistamiento" value="registrada" class="accent-amber-500 w-4 h-4">
                                <div class="text-sm">
                                    <strong class="font-bold text-slate-800 block">Vincular a mascota registrada</strong>
                                    <span class="text-xs text-slate-500">Sé a qué reporte oficial corresponde esta vista.</span>
                                </div>
                            </label>
                        </div>

                        <!-- Opción A: Selector compacto con buscador y foto si se selecciona Mascota Registrada -->
                        <div id="container-mascota-select" class="hidden space-y-3 pt-2 border-t border-amber-100">
                            <label for="mascota_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Seleccionar Mascota
                            </label>

                            <div class="relative">
                                <input type="text" id="mascota-search" placeholder="Buscar por nombre o especie" class="w-full px-3.5 py-2.5 rounded-xl border border-blue-100 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                                <i class="ph ph-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            </div>

                            <div id="mascota-list" class="max-h-56 overflow-y-auto space-y-2 rounded-xl border border-slate-100 bg-slate-50/50 p-2">
                                @foreach($mascotas as $mascota)
                                    @php
                                        $fotoUrl = $mascota->foto_url;
                                        $isSelected = old('mascota_id') == $mascota->id;
                                    @endphp
                                    <label class="mascota-option-card flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all {{ $isSelected ? 'border-amber-500 bg-amber-50 shadow-sm' : 'border-transparent bg-white hover:border-amber-200 hover:bg-amber-50/40' }}">
                                        <input type="radio" name="mascota_option" value="{{ $mascota->id }}" class="sr-only" {{ $isSelected ? 'checked' : '' }}>
                                        <div class="w-11 h-11 rounded-lg overflow-hidden bg-slate-100 shrink-0 flex items-center justify-center border border-slate-200">
                                            @if ($fotoUrl)
                                                <img src="{{ $fotoUrl }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="ph ph-paw-print text-lg text-slate-400"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-800 text-sm">{{ $mascota->nombre }}</div>
                                            <div class="text-xs text-slate-500 truncate">
                                                {{ ucfirst($mascota->especie->nombre ?? 'Animal') }} · {{ $mascota->raza ?? 'Mestizo' }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <select name="mascota_id" id="mascota_id" class="hidden">
                                <option value="">-- Selecciona una mascota de la comunidad --</option>
                                @foreach($mascotas as $mascota)
                                    <option value="{{ $mascota->id }}" {{ old('mascota_id') == $mascota->id ? 'selected' : '' }}>
                                        {{ $mascota->nombre }} — {{ ucfirst($mascota->especie->nombre ?? 'Animal') }} ({{ $mascota->raza ?? 'Mestizo' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Opción B: Campos guiados para describir mascota no identificada -->
                        <div id="container-unknown-fields" class="space-y-4 pt-2">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Especie</label>
                                    <select id="quick_especie" class="w-full px-3.5 py-3 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                                        <option value="">-- Selecciona --</option>
                                        @foreach($especies as $especie)
                                            <option value="{{ ucfirst($especie->nombre) }}">{{ ucfirst($especie->nombre) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Tamaño aproximado</label>
                                    <select id="quick_tamano" class="w-full px-3.5 py-3 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                                        <option value="">-- Selecciona --</option>
                                        <option value="Pequeño">Pequeño</option>
                                        <option value="Mediano">Mediano</option>
                                        <option value="Grande">Grande</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Color / Pelaje</label>
                                    <input type="text" id="quick_color" placeholder="Ej. Café con manchas blancas" class="w-full px-3.5 py-3 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Ubicación interactiva con OpenStreetMap -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600">
                                <i class="ph ph-map-pin text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-blue-950">Ubicación del Avistamiento</h2>
                                <p class="text-xs text-slate-400">Marca en el mapa el lugar donde fue vista la mascota</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buscador Autocompletado -->
                    <div class="space-y-2 relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Buscar Calle o Colonia en Matamoros / México
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2 relative">
                            <div class="relative flex-1">
                                <input type="text" id="map-search-input" autocomplete="off" placeholder="Ej. Calle 6, Col. Jardines, Matamoros..." class="w-full px-4 py-3 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
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
                            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="ubicacion_lat" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Ubicación / Colonia <span class="text-amber-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lat" id="ubicacion_lat" value="{{ old('ubicacion_lat') }}" required placeholder="Ej. Col. Jardines, Matamoros" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="ubicacion_lng" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Referencia o Coordenadas <span class="text-amber-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lng" id="ubicacion_lng" value="{{ old('ubicacion_lng') }}" required placeholder="Se actualiza al mover el punto en el mapa" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm">
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Fotografía y Detalles -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600">
                            <i class="ph ph-image text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Fotografía y Descripción</h2>
                            <p class="text-xs text-slate-400">Adjunta una imagen si lograste fotografiarlo y añade observaciones</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="foto" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Fotografía del Avistamiento <span class="text-slate-400 font-normal">(Opcional)</span>
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="w-full bg-amber-50/50 border border-amber-100 p-2.5 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 file:mr-4 file:py-2 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-white hover:file:bg-amber-600 cursor-pointer transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="descripcion" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Descripción / Detalles del Avistamiento <span class="text-amber-600">*</span>
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="4" required placeholder="Ej. Visto caminando sobre Av. Lauro Villar alrededor de las 3:00 PM. Portaba collar rojo sin placa, parecía asustado..." class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm leading-relaxed">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <!-- Botones de Acción -->
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

<!-- Script de Leaflet y Comportamiento de Formulario -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle entre Mascota Desconocida y Mascota Registrada
        var radioUnknown = document.querySelector('input[name="tipo_avistamiento"][value="desconocida"]');
        var radioKnown = document.querySelector('input[name="tipo_avistamiento"][value="registrada"]');
        var cardUnknown = document.getElementById('card-type-unknown');
        var cardKnown = document.getElementById('card-type-known');
        var containerSelect = document.getElementById('container-mascota-select');
        var containerUnknown = document.getElementById('container-unknown-fields');
        var hiddenSelect = document.getElementById('mascota_id');

        function updateTypeMode() {
            if (radioKnown.checked) {
                cardKnown.classList.add('border-amber-500', 'border-2');
                cardKnown.classList.remove('border-slate-200');
                cardUnknown.classList.remove('border-amber-500', 'border-2');
                cardUnknown.classList.add('border-slate-200');
                
                containerSelect.classList.remove('hidden');
                containerUnknown.classList.add('hidden');
            } else {
                cardUnknown.classList.add('border-amber-500', 'border-2');
                cardUnknown.classList.remove('border-slate-200');
                cardKnown.classList.remove('border-amber-500', 'border-2');
                cardKnown.classList.add('border-slate-200');
                
                containerSelect.classList.add('hidden');
                containerUnknown.classList.remove('hidden');
            }
        }

        radioUnknown.addEventListener('change', updateTypeMode);
        radioKnown.addEventListener('change', updateTypeMode);

        document.querySelectorAll('.mascota-option-card').forEach(function (card) {
            card.addEventListener('click', function () {
                var radio = card.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    if (hiddenSelect) {
                        hiddenSelect.value = radio.value;
                    }
                }

                document.querySelectorAll('.mascota-option-card').forEach(function (item) {
                    item.classList.remove('border-amber-500', 'bg-amber-50', 'shadow-sm');
                    item.classList.add('border-transparent', 'bg-white');
                });

                card.classList.remove('border-transparent', 'bg-white');
                card.classList.add('border-amber-500', 'bg-amber-50', 'shadow-sm');
            });
        });

        var mascotaSearch = document.getElementById('mascota-search');
        var mascotaList = document.getElementById('mascota-list');

        if (mascotaSearch && mascotaList) {
            mascotaSearch.addEventListener('input', function () {
                var term = this.value.toLowerCase().trim();
                var cards = mascotaList.querySelectorAll('.mascota-option-card');

                cards.forEach(function (card) {
                    var text = card.textContent.toLowerCase();
                    card.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }

        // Auto-ensamblaje sutil de descripción con los campos rápidos si la descripción está vacía
        var qEspecie = document.getElementById('quick_especie');
        var qTamano = document.getElementById('quick_tamano');
        var qColor = document.getElementById('quick_color');
        var textareaDesc = document.getElementById('descripcion');

        function syncQuickDescription() {
            var parts = [];
            if (qEspecie && qEspecie.value) parts.push(qEspecie.value);
            if (qTamano && qTamano.value) parts.push('de tamaño ' + qTamano.value.toLowerCase());
            if (qColor && qColor.value) parts.push('de color ' + qColor.value);

            if (parts.length > 0) {
                var prefix = parts.join(' ');
                if (!textareaDesc.value || textareaDesc.dataset.autoGen === 'true') {
                    textareaDesc.value = prefix + '. ';
                    textareaDesc.dataset.autoGen = 'true';
                }
            }
        }

        if (qEspecie) qEspecie.addEventListener('change', syncQuickDescription);
        if (qTamano) qTamano.addEventListener('change', syncQuickDescription);
        if (qColor) qColor.addEventListener('input', syncQuickDescription);

        textareaDesc.addEventListener('input', function() {
            this.dataset.autoGen = 'false';
        });

        // OpenStreetMap + Nominatim + Geolocalización GPS
        var defaultLat = 25.8690;
        var defaultLng = -97.5027;

        var inputLat = document.getElementById('ubicacion_lat');
        var inputLng = document.getElementById('ubicacion_lng');
        var searchInput = document.getElementById('map-search-input');
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
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=es`)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var displayName = data.display_name || '';
                    var parts = displayName.split(',');
                    var shortAddress = parts.slice(0, 3).join(',').trim();
                    updateInputs(lat, lng, shortAddress);
                });
        }

        function getUserLocation() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;

                        map.setView([userLat, userLng], 15);
                        marker.setLatLng([userLat, userLng]);
                        reverseGeocode(userLat, userLng);
                    },
                    function () {
                        console.log('Permiso denegado. Usando Matamoros por defecto.');
                    }
                );
            }
        }

        getUserLocation();

        var myLocationBtn = document.getElementById('btn-my-location');
        if (myLocationBtn) {
            myLocationBtn.addEventListener('click', getUserLocation);
        }

        // Autocompletado predictivo en vivo al escribir la calle
        var searchSuggestions = document.getElementById('search-suggestions');
        if (searchInput && searchSuggestions) {
            var debounceTimer = null;

            function hideSuggestions() {
                searchSuggestions.classList.add('hidden');
                searchSuggestions.innerHTML = '';
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
                            searchSuggestions.innerHTML = '';
                            if (!data || data.length === 0) {
                                hideSuggestions();
                                return;
                            }

                            data.forEach(function (item) {
                                var li = document.createElement('li');
                                li.className = 'px-4 py-3 hover:bg-amber-50 cursor-pointer flex items-center gap-2.5 text-xs text-slate-700 transition-colors border-b border-slate-100 last:border-0';
                                
                                var shortTitle = item.display_name.split(',').slice(0, 3).join(',').trim();
                                li.innerHTML = `<i class="ph ph-map-pin text-amber-500 text-base flex-shrink-0"></i> <div><strong class="block text-slate-800 font-bold">${shortTitle}</strong><span class="text-[10px] text-slate-400 block">${item.display_name}</span></div>`;

                                li.addEventListener('click', function () {
                                    var lat = parseFloat(item.lat);
                                    var lon = parseFloat(item.lon);
                                    searchInput.value = shortTitle;
                                    hideSuggestions();

                                    map.setView([lat, lon], 16);
                                    marker.setLatLng([lat, lon]);
                                    updateInputs(lat, lon, shortTitle);
                                });

                                searchSuggestions.appendChild(li);
                            });

                            searchSuggestions.classList.remove('hidden');
                        })
                        .catch(function () { hideSuggestions(); });
                }, 300);
            });

            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
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
