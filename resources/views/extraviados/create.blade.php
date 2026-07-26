@extends('layout.admin')

@section('content')

<!-- Estilos Leaflet para OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <!-- Encabezado idéntico al Index de Extraviados -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-4xl font-extrabold text-blue-950 flex items-center gap-3">
                <i class="ph ph-siren text-blue-500 animate-pulse"></i> 
                Reportar Mascota Extraviada
            </h1>
            <p class="mt-2 text-slate-500 font-light">
                Completa los datos del reporte y ubica el lugar exacto en el mapa.
            </p>
        </div>
        <a href="{{ route('extraviados.index') }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-3 px-6 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 whitespace-nowrap">
            <i class="ph ph-arrow-left text-xl"></i> Regresar a Alertas
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

            <form action="{{ route('extraviados.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="tipo_reporte_id" value="1">
                <input type="hidden" name="user_id" value="{{ auth()->id() ?? 1 }}">

                <!-- Sección 1: Selección de Mascota -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                            <i class="ph ph-paw-print text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Información de la Mascota</h2>
                            <p class="text-xs text-slate-400">Selecciona la mascota que ha sido extraviada</p>
                        </div>
                    </div>

                    <div class="bg-blue-50/30 p-4 sm:p-5 rounded-2xl border border-blue-100/60 space-y-3">
                        <label for="mascota_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Mascota Registrada <span class="text-blue-600">*</span>
                        </label>
                        <select name="mascota_id" id="mascota_id" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                            <option value="" data-foto="">-- Selecciona una mascota --</option>
                            @foreach($mascotas as $mascota)
                                @php
                                    $fotoUrl = $mascota->foto ? (Str::startsWith($mascota->foto, 'http') ? $mascota->foto : asset('storage/' . $mascota->foto)) : 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=300&q=80';
                                @endphp
                                <option value="{{ $mascota->id }}" data-nombre="{{ $mascota->nombre }}" data-foto="{{ $fotoUrl }}" {{ old('mascota_id') == $mascota->id ? 'selected' : '' }}>
                                    🐾 {{ $mascota->nombre }} — {{ ucfirst($mascota->especie->nombre ?? 'Animal') }} ({{ $mascota->raza ?? 'Mestizo' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400">
                            ¿Aún no has registrado a tu mascota? 
                            <a href="{{ route('mascotas.create') }}" class="text-blue-600 font-bold hover:underline">Registrar nueva mascota</a>
                        </p>
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
                                <p class="text-xs text-slate-400">Escribe la calle/colonia para ver sugerencias o haz clic en el mapa</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buscador Autocompletado en Tiempo Real -->
                    <div class="space-y-2 relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Buscar Calle o Colonia (Sugerencias dinámicas en tiempo real)
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2 relative">
                            <div class="relative flex-1">
                                <input type="text" id="map-search-input" autocomplete="off" placeholder="Escribe para buscar (ej. Calle Sexta, Col. Jardines, Matamoros...)" class="w-full px-4 py-3 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <!-- Desplegable de Coincidencias en Tiempo Real -->
                                <ul id="search-suggestions" class="absolute left-0 right-0 top-full mt-1 bg-white border border-blue-100 rounded-2xl shadow-xl z-50 max-h-60 overflow-y-auto divide-y divide-slate-100 hidden"></ul>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" id="btn-search-map" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-xl text-xs flex items-center gap-1.5 transition-colors">
                                    <i class="ph ph-magnifying-glass text-base"></i> Buscar
                                </button>
                                <button type="button" id="btn-my-location" title="Obtener mi ubicación actual" class="bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold px-4 py-3 rounded-xl text-xs flex items-center gap-1.5 transition-colors border border-blue-100 whitespace-nowrap">
                                    <i class="ph ph-crosshair text-base"></i> Mi Ubicación
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
                            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>

                        <!-- Colonia / Ubicación (Auto-llenado por el mapa) -->
                        <div class="space-y-2">
                            <label for="ubicacion_lat" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Ubicación / Colonia <span class="text-blue-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lat" id="ubicacion_lat" value="{{ old('ubicacion_lat') }}" required placeholder="Ej. Col. San Francisco, Matamoros" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>

                        <!-- Referencia / Coordenadas (Auto-llenado) -->
                        <div class="space-y-2">
                            <label for="ubicacion_lng" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Referencia o Coordenadas <span class="text-blue-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lng" id="ubicacion_lng" value="{{ old('ubicacion_lng') }}" required placeholder="Se actualiza al mover el punto en el mapa" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Fotografías y Descripción (Área Unificada de Fotografías) -->
                <div class="space-y-6 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                            <i class="ph ph-image text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Fotografías y Descripción</h2>
                            <p class="text-xs text-slate-400">Verifica la foto oficial de tu mascota o sube una fotografía reciente</p>
                        </div>
                    </div>

                    <!-- Contenedor Integrado de Fotografías (Foto Automática + Foto Adicional) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Columna A: Foto Oficial Registrada (Vista previa dinámica) -->
                        <div class="bg-blue-50/40 p-4 sm:p-5 rounded-2xl border border-blue-100/80 space-y-3 flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Foto Oficial de la Mascota
                                </label>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                    Automática
                                </span>
                            </div>

                            <div id="mascota-photo-preview-container" class="hidden flex items-center gap-4 bg-white p-3 rounded-xl border border-blue-100 shadow-sm">
                                <div class="relative w-20 h-20 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                    <img id="mascota-photo-img" src="" alt="Foto de la Mascota" class="w-full h-full object-cover">
                                </div>
                                <div class="space-y-1">
                                    <h4 id="mascota-photo-title" class="text-xs font-bold text-slate-800">Foto asignada</h4>
                                    <p class="text-[11px] text-slate-500 leading-tight">Esta fotografía de perfil se utilizará por defecto en la alerta.</p>
                                </div>
                            </div>

                            <div id="mascota-photo-placeholder" class="p-5 bg-white rounded-xl border border-dashed border-slate-200 text-center text-xs text-slate-400">
                                <i class="ph ph-paw-print text-2xl text-slate-300 block mb-1"></i>
                                Selecciona una mascota en el paso 1 para cargar automáticamente su foto de perfil.
                            </div>
                        </div>

                        <!-- Columna B: Subir Foto Reciente o Adicional -->
                        <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3 flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <label for="foto" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Foto Reciente o Adicional <span class="text-slate-400 font-normal">(Opcional)</span>
                                </label>
                            </div>
                            
                            <input type="file" name="foto" id="foto" accept="image/*" class="w-full bg-white border border-slate-200 p-2.5 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition-all">
                            
                            <p class="text-[11px] text-slate-500">
                                📸 ¿Tienes una foto tomada hoy o del momento exacto? Súbela para actualizar la alerta.
                            </p>
                        </div>

                    </div>

                    <!-- Descripción -->
                    <div class="space-y-2">
                        <label for="descripcion" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Descripción / Señas Particulares <span class="text-blue-600">*</span>
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="4" required placeholder="Describe si traía collar, cascabel, señas particulares o recompensa ofrecida..." class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('extraviados.index') }}" class="w-full sm:w-auto text-center px-6 py-3 rounded-full text-slate-500 font-bold hover:bg-slate-100 transition-colors text-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-full shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="ph ph-paw-print text-xl"></i> Publicar Alerta de Extravío
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
        // Manejo de vista previa de la foto oficial de la mascota seleccionada en la Sección de Fotografías
        var mascotaSelect = document.getElementById('mascota_id');
        var previewContainer = document.getElementById('mascota-photo-preview-container');
        var previewPlaceholder = document.getElementById('mascota-photo-placeholder');
        var previewImg = document.getElementById('mascota-photo-img');
        var previewTitle = document.getElementById('mascota-photo-title');

        function updateMascotaPreview() {
            if (!mascotaSelect) return;
            var selectedOption = mascotaSelect.options[mascotaSelect.selectedIndex];
            var foto = selectedOption ? selectedOption.getAttribute('data-foto') : '';
            var nombre = selectedOption ? selectedOption.getAttribute('data-nombre') : '';

            if (foto && selectedOption.value !== '') {
                previewImg.src = foto;
                previewTitle.textContent = 'Foto asignada de ' + (nombre || 'la mascota');
                previewContainer.classList.remove('hidden');
                if (previewPlaceholder) previewPlaceholder.classList.add('hidden');
            } else {
                previewContainer.classList.add('hidden');
                if (previewPlaceholder) previewPlaceholder.classList.remove('hidden');
            }
        }

        if (mascotaSelect) {
            mascotaSelect.addEventListener('change', updateMascotaPreview);
            updateMascotaPreview();
        }

        // Coordenadas por defecto (Matamoros, Tamaulipas)
        var defaultLat = 25.8690;
        var defaultLng = -97.5027;

        var inputLat = document.getElementById('ubicacion_lat');
        var inputLng = document.getElementById('ubicacion_lng');
        var coordsBadge = document.getElementById('map-coords-badge');
        var searchInput = document.getElementById('map-search-input');
        var suggestionsList = document.getElementById('search-suggestions');
        var searchBtn = document.getElementById('btn-search-map');

        // Inicializar mapa de OpenStreetMap
        var map = L.map('osm-map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Marcador con ícono de mapa
        var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateInputs(lat, lng, addressName) {
            if (addressName) {
                inputLat.value = addressName;
            } else if (!inputLat.value) {
                inputLat.value = 'Lat: ' + lat.toFixed(5) + ', Lng: ' + lng.toFixed(5);
            }
            inputLng.value = lat.toFixed(6) + ', ' + lng.toFixed(6);
            if (coordsBadge) {
                coordsBadge.textContent = 'Lat: ' + lat.toFixed(4) + ', Lng: ' + lng.toFixed(4);
            }
        }

        // Reverse Geocoding usando la API de Nominatim
        function reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var displayName = data.display_name || '';
                    var parts = displayName.split(',');
                    var shortAddress = parts.slice(0, 3).join(',').trim();
                    updateInputs(lat, lng, shortAddress);
                })
                .catch(function() {
                    updateInputs(lat, lng, '');
                });
        }

        // Función para solicitar ubicación GPS del usuario
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
                    function (error) {
                        console.log('Permiso de ubicación no otorgado. Usando Matamoros por defecto.');
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            }
        }

        // Solicitar ubicación al cargar la vista
        getUserLocation();

        // Botón "Mi Ubicación"
        var myLocationBtn = document.getElementById('btn-my-location');
        if (myLocationBtn) {
            myLocationBtn.addEventListener('click', function() {
                getUserLocation();
            });
        }

        // Mover el marcador por arrastre
        marker.on('dragend', function (e) {
            var pos = marker.getLatLng();
            reverseGeocode(pos.lat, pos.lng);
        });

        // Mover el marcador por clic en el mapa
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
            suggestionsList.classList.add('hidden');
        });

        // Autocompletado de coincidencias en tiempo real mientras se escribe (Debounce 300ms)
        var debounceTimer = null;

        searchInput.addEventListener('input', function() {
            var query = searchInput.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 3) {
                suggestionsList.classList.add('hidden');
                suggestionsList.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(function() {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`)
                    .then(function(res) { return res.json(); })
                    .then(function(results) {
                        suggestionsList.innerHTML = '';
                        if (results && results.length > 0) {
                            results.forEach(function(item) {
                                var li = document.createElement('li');
                                li.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-start gap-2.5 text-xs text-slate-700 transition-colors';
                                
                                var parts = item.display_name.split(',');
                                var title = parts.slice(0, 2).join(',').trim();
                                var subtitle = parts.slice(2, 5).join(',').trim();

                                li.innerHTML = `
                                    <i class="ph ph-map-pin text-blue-600 text-base mt-0.5 flex-shrink-0"></i>
                                    <div class="overflow-hidden">
                                        <div class="font-bold text-blue-950 truncate">${title}</div>
                                        <div class="text-[11px] text-slate-400 truncate">${subtitle}</div>
                                    </div>
                                `;

                                li.addEventListener('click', function() {
                                    var lat = parseFloat(item.lat);
                                    var lon = parseFloat(item.lon);
                                    map.setView([lat, lon], 16);
                                    marker.setLatLng([lat, lon]);
                                    searchInput.value = title;
                                    reverseGeocode(lat, lon);
                                    suggestionsList.classList.add('hidden');
                                });

                                suggestionsList.appendChild(li);
                            });
                            suggestionsList.classList.remove('hidden');
                        } else {
                            suggestionsList.classList.add('hidden');
                        }
                    })
                    .catch(function(err) {
                        console.error(err);
                        suggestionsList.classList.add('hidden');
                    });
            }, 300);
        });

        // Búsqueda manual por botón o Enter
        function executeSearch() {
            var query = searchInput.value.trim();
            if (!query) return;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(function(res) { return res.json(); })
                .then(function(results) {
                    if (results && results.length > 0) {
                        var first = results[0];
                        var lat = parseFloat(first.lat);
                        var lon = parseFloat(first.lon);
                        map.setView([lat, lon], 15);
                        marker.setLatLng([lat, lon]);
                        reverseGeocode(lat, lon);
                        suggestionsList.classList.add('hidden');
                    } else {
                        alert('No se encontraron ubicaciones para la búsqueda ingresada.');
                    }
                })
                .catch(function(err) {
                    console.error(err);
                });
        }

        searchBtn.addEventListener('click', executeSearch);

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                executeSearch();
            }
        });

        // Ocultar la lista de sugerencias al hacer clic fuera del buscador
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                suggestionsList.classList.add('hidden');
            }
        });
    });
</script>

@endsection
