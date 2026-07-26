@extends('layout.admin')

@section('content')

<!-- Estilos Leaflet para OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <!-- Encabezado con Botón de Regresar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-100 pb-6 max-w-4xl mx-auto">
        <div>
            <h1 class="text-3xl font-extrabold text-blue-950 flex items-center gap-2">
                <i class="ph ph-siren text-rose-600"></i>
                Publicar Alerta de Extravío
            </h1>
            <p class="text-xs text-slate-400 mt-1">Registra los datos de tu mascota extraviada para que la comunidad te ayude a encontrarla</p>
        </div>

        <a href="{{ route('extraviados.index') }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-2.5 px-5 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 text-sm">
            <i class="ph ph-arrow-left text-lg"></i> Cancelar
        </a>
    </div>

    <!-- Contenedor del Formulario -->
    <div class="w-full max-w-4xl mx-auto">

        <!-- Mensajes de Error de Validación -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm">
                <div class="font-bold mb-1 flex items-center gap-2">
                    <i class="ph ph-warning-circle text-lg"></i> Por favor corrige los siguientes errores:
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 sm:p-8 md:p-10">
            
            <form action="{{ route('extraviados.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="tipo_reporte_id" value="1">
                <input type="hidden" name="user_id" value="{{ auth()->id() ?? 1 }}">

                <!-- Sección 1: Selección de Mascota de Mis Mascotas -->
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

                    <div class="bg-blue-50/30 p-4 sm:p-5 rounded-2xl border border-blue-100/60 space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Mis Mascotas Registradas <span class="text-blue-600">*</span>
                            </label>
                            <span class="text-xs text-slate-400">Selecciona tu mascota</span>
                        </div>

                        <input type="hidden" name="mascota_id" id="mascota_id" value="{{ old('mascota_id') }}" required>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="pet-cards-grid">
                            @forelse($mascotas as $mascota)
                                @php
                                    $fotoUrl = $mascota->foto ? (Str::startsWith($mascota->foto, 'http') ? $mascota->foto : asset('storage/' . $mascota->foto)) : asset('storage/mascotas/default.jpg');
                                @endphp
                                <div data-id="{{ $mascota->id }}" data-nombre="{{ $mascota->nombre }}" data-foto="{{ $fotoUrl }}" class="pet-card-item cursor-pointer flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-2xl hover:border-blue-500 hover:shadow-sm transition-all text-left group {{ old('mascota_id') == $mascota->id ? 'border-blue-600 bg-blue-50/20 ring-2 ring-blue-500/20' : '' }}">
                                    <!-- Foto en pequeño -->
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200 group-hover:scale-105 transition-transform">
                                        <img src="{{ $fotoUrl }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover">
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-slate-800 capitalize truncate">{{ $mascota->nombre }}</h4>
                                        <p class="text-xs text-slate-400 truncate">{{ ucfirst($mascota->especie->nombre ?? 'Mascota') }} • {{ $mascota->raza ?? 'Mestizo' }}</p>
                                    </div>

                                    <div class="pet-check-badge w-5 h-5 rounded-full border border-slate-300 flex items-center justify-center text-white text-xs flex-shrink-0 {{ old('mascota_id') == $mascota->id ? 'bg-blue-600 border-blue-600' : '' }}">
                                        <i class="ph ph-check font-bold {{ old('mascota_id') == $mascota->id ? '' : 'hidden' }}"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full bg-white border-2 border-dashed border-blue-200 rounded-2xl p-6 text-center space-y-3">
                                    <i class="ph ph-check-circle text-4xl text-emerald-500"></i>
                                    <h3 class="text-sm font-bold text-slate-800">No hay mascotas pendientes por reportar</h3>
                                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Todas tus mascotas registradas ya cuentan con una alerta de extravío activa publicada o están a salvo en casa.</p>
                                    <div class="flex flex-wrap justify-center gap-3 pt-2">
                                        <a href="{{ route('extraviados.index') }}" class="inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold py-2.5 px-5 rounded-full text-xs transition-all border border-blue-200">
                                            <i class="ph ph-siren text-base"></i> Ver Alertas Publicadas
                                        </a>
                                        <a href="{{ route('mascotas.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-full text-xs transition-all shadow-sm">
                                            <i class="ph ph-plus-circle text-base"></i> Registrar Nueva Mascota
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <p class="text-xs text-slate-400 pt-1">
                            ¿Falta alguna mascota? 
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

                        <!-- Colonia / Ubicación -->
                        <div class="space-y-2">
                            <label for="ubicacion_lat" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Ubicación / Colonia <span class="text-blue-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lat" id="ubicacion_lat" value="{{ old('ubicacion_lat') }}" required placeholder="Ej. Col. San Francisco, Matamoros" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                        </div>

                        <!-- Referencia / Coordenadas -->
                        <div class="space-y-2">
                            <label for="ubicacion_lng" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Referencia o Coordenadas <span class="text-blue-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lng" id="ubicacion_lng" value="{{ old('ubicacion_lng') }}" required placeholder="Se actualiza al mover el punto en el mapa" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
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
                            <p class="text-xs text-slate-400">Verifica la foto oficial de tu mascota o sube una fotografía reciente</p>
                        </div>
                    </div>

                    <!-- Contenedor Integrado de Fotografías -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Columna A: Foto Oficial Registrada -->
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

                            <div id="mascota-photo-placeholder" class="bg-white p-4 rounded-xl border border-dashed border-slate-200 text-center py-6">
                                <i class="ph ph-image-square text-3xl text-slate-300 mb-1"></i>
                                <p class="text-xs text-slate-400 font-medium">Selecciona una mascota arriba para cargar su fotografía registrada.</p>
                            </div>
                        </div>

                        <!-- Columna B: Fotografía Reciente Opcional -->
                        <div class="bg-blue-50/40 p-4 sm:p-5 rounded-2xl border border-blue-100/80 space-y-3 flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <label for="foto" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Fotografía Adicional / Reciente
                                </label>
                                <span class="text-[10px] text-slate-400 font-medium">Opcional</span>
                            </div>

                            <div class="space-y-2">
                                <input type="file" name="foto" id="foto" accept="image/*" class="w-full bg-white border border-blue-100 p-2.5 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition-all">
                                <p class="text-[11px] text-slate-400">Si la mascota cambio de apariencia o tienes una foto del momento del extravío.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Descripción / Señas Particulares -->
                    <div class="space-y-2">
                        <label for="descripcion" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Descripción y Señas Particulares <span class="text-blue-600">*</span>
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="4" required placeholder="Describe detalles específicos (ej. Trae collar rojo con cascabel, una mancha blanca en la pata izquierda, responde al nombre de 'Fiddo', asustado por cohetes...)" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm leading-relaxed">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('extraviados.index') }}" class="w-full sm:w-auto text-center px-6 py-3 rounded-full text-slate-500 font-bold hover:bg-slate-100 transition-colors text-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-full shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="ph ph-paper-plane-tilt text-xl"></i> Publicar Alerta de Extravío
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- Script de Leaflet y Selección de Mascotas -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var petItems = document.querySelectorAll('.pet-card-item');
        var inputMascotaId = document.getElementById('mascota_id');
        var previewContainer = document.getElementById('mascota-photo-preview-container');
        var previewPlaceholder = document.getElementById('mascota-photo-placeholder');
        var previewImg = document.getElementById('mascota-photo-img');
        var previewTitle = document.getElementById('mascota-photo-title');

        petItems.forEach(function(item) {
            item.addEventListener('click', function() {
                petItems.forEach(function(p) {
                    p.classList.remove('border-blue-600', 'bg-blue-50/40', 'ring-2', 'ring-blue-500/30', 'scale-[1.02]');
                    p.classList.add('border-slate-200', 'bg-white');
                    var badge = p.querySelector('.pet-check-badge');
                    if (badge) {
                        badge.classList.remove('bg-blue-600', 'border-blue-600', 'scale-110');
                        badge.classList.add('border-slate-300');
                        var icon = badge.querySelector('.ph-check');
                        if (icon) icon.classList.add('hidden');
                    }
                });

                this.classList.remove('border-slate-200', 'bg-white');
                this.classList.add('border-blue-600', 'bg-blue-50/40', 'ring-2', 'ring-blue-500/30', 'scale-[1.02]');
                var badge = this.querySelector('.pet-check-badge');
                if (badge) {
                    badge.classList.remove('border-slate-300');
                    badge.classList.add('bg-blue-600', 'border-blue-600', 'scale-110');
                    var icon = badge.querySelector('.ph-check');
                    if (icon) icon.classList.remove('hidden');
                }

                var mId = this.getAttribute('data-id');
                var mFoto = this.getAttribute('data-foto');
                var mNombre = this.getAttribute('data-nombre');

                if (inputMascotaId) inputMascotaId.value = mId;

                if (previewImg && mFoto) {
                    previewImg.src = mFoto;
                    previewTitle.textContent = 'Foto asignada de ' + (mNombre || 'la mascota');
                    if (previewContainer) previewContainer.classList.remove('hidden');
                    if (previewPlaceholder) previewPlaceholder.classList.add('hidden');
                }
            });
        });

        // Auto-selección inteligente de la primera mascota registrada si no venía una preseleccionada
        var preselected = document.querySelector('.pet-card-item.border-blue-600');
        if (preselected) {
            preselected.click();
        } else if (petItems.length > 0) {
            petItems[0].click();
        }

        // Coordenadas por defecto (Matamoros)
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
            attribution: '&copy; OpenStreetMap'
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
                    }
                );
            }
        }

        var myLocationBtn = document.getElementById('btn-my-location');
        if (myLocationBtn) {
            myLocationBtn.addEventListener('click', getUserLocation);
        }

        // Autocompletado predictivo al escribir
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
