@extends('layout.admin')

@section('content')

<!-- Estilos Leaflet para OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <!-- Encabezado con Botón de Regresar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-100 pb-6">
        <a href="{{ route('extraviados.index') }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-2.5 px-5 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 text-sm">
            <i class="ph ph-arrow-left text-lg"></i> Regresar a Alertas
        </a>

        @if(auth()->id() == $reporte->user_id)
            <div class="flex items-center gap-3">
                <a href="{{ route('extraviados.edit', $reporte->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 text-sm">
                    <i class="ph ph-pencil-simple text-lg"></i> Editar Mi Alerta
                </a>
            </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <div class="w-full max-w-6xl mx-auto space-y-10">

        <!-- Ficha de Mascota Extraviada -->
        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12 gap-0">
            
            <!-- Foto de la Mascota -->
            <div class="lg:col-span-5 relative bg-slate-100 h-80 lg:h-auto min-h-[320px]">
                @php
                    $fotoUrl = $reporte->foto ? (Str::startsWith($reporte->foto, 'http') ? $reporte->foto : asset('storage/' . $reporte->foto)) : ($reporte->mascota->foto ? asset('storage/' . $reporte->mascota->foto) : asset('storage/mascotas/default.jpg'));
                @endphp
                <img src="{{ $fotoUrl }}" alt="{{ $reporte->mascota->nombre }}" class="w-full h-full object-cover">
                <div class="absolute top-4 left-4 z-10 bg-rose-600 text-white text-xs uppercase tracking-wider font-extrabold px-4 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
                    <i class="ph ph-siren text-lg"></i> Se busca
                </div>
            </div>

            <!-- Información Detallada de la Mascota -->
            <div class="lg:col-span-7 p-6 md:p-8 flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs uppercase tracking-wider font-extrabold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                {{ ucfirst($reporte->mascota->especie->nombre ?? 'Mascota') }}
                            </span>
                            <h1 class="text-4xl font-extrabold text-blue-950 mt-2 capitalize">{{ $reporte->mascota->nombre }}</h1>
                        </div>
                        <span class="text-xs text-slate-400 font-semibold flex items-center gap-1">
                            <i class="ph ph-clock text-blue-500"></i> Extraviado el {{ \Carbon\Carbon::parse($reporte->fecha)->format('d \d\e M, Y') }}
                        </span>
                    </div>

                    <!-- Cuadrícula de Datos -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6">
                        <div class="bg-blue-50/50 p-3 rounded-2xl border border-blue-100/60">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Raza</span>
                            <strong class="text-sm text-slate-800 capitalize">{{ $reporte->mascota->raza ?? 'Mestizo' }}</strong>
                        </div>
                        <div class="bg-blue-50/50 p-3 rounded-2xl border border-blue-100/60">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Ubicación</span>
                            <strong class="text-sm text-slate-800 capitalize truncate block">{{ $reporte->ubicacion_lat }}</strong>
                        </div>
                        <div class="bg-blue-50/50 p-3 rounded-2xl border border-blue-100/60">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Reportado por</span>
                            <strong class="text-sm text-slate-800 capitalize">{{ $reporte->user->name ?? 'Usuario' }}</strong>
                        </div>
                    </div>

                    <!-- Descripción / Señas Particulares -->
                    <div class="mt-6 space-y-2">
                        <h3 class="text-xs uppercase tracking-wider font-bold text-slate-500">Descripción y Señas Particulares</h3>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-sm text-slate-700 leading-relaxed italic">
                            "{{ $reporte->descripcion }}"
                        </div>
                    </div>
                </div>

                <!-- Botones de Contacto con el Dueño -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row gap-3 items-center">
                    @if($reporte->user && $reporte->user->telefono)
                        <a href="tel:{{ $reporte->user->telefono }}" class="w-full sm:w-auto flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-5 rounded-2xl transition-all shadow flex items-center justify-center gap-2 text-sm">
                            <i class="ph ph-phone text-xl"></i> Llamar al Dueño ({{ $reporte->user->telefono }})
                        </a>
                        <a href="https://wa.me/52{{ preg_replace('/[^0-9]/', '', $reporte->user->telefono) }}?text=Hola,%20vi%20tu%20publicación%20de%20extravío%20de%20{{ urlencode($reporte->mascota->nombre) }}" target="_blank" class="w-full sm:w-auto bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold py-3 px-5 rounded-2xl transition-all border border-emerald-200 flex items-center justify-center gap-2 text-sm">
                            <i class="ph ph-whatsapp-logo text-xl text-emerald-600"></i> WhatsApp
                        </a>
                    @else
                        <div class="text-xs text-slate-400 italic">No hay teléfono de contacto registrado para este usuario.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mapa de la Zona de Extravío -->
        <div class="bg-white rounded-3xl shadow-sm border border-blue-50 p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                    <i class="ph ph-map-pin text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-blue-950">Lugar de Desaparición</h2>
                    <p class="text-xs text-slate-400">{{ $reporte->ubicacion_lat }} — Coordenadas: {{ $reporte->ubicacion_lng }}</p>
                </div>
            </div>
            <div id="show-osm-map" class="w-full h-72 rounded-2xl border border-blue-100 shadow-inner overflow-hidden z-0"></div>
        </div>

        <!-- Sección de Pistas y Avistamientos de la Comunidad -->
        <div class="space-y-6 pt-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-blue-950 flex items-center gap-2">
                        <i class="ph ph-binoculars text-amber-500"></i>
                        Pistas y Avistamientos de la Comunidad
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Reportes de vecinos que han visto o tienen información sobre esta mascota</p>
                </div>

                <a href="#form-reportar-avistamiento" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-full shadow-md transition-all flex items-center justify-center gap-2 text-sm">
                    <i class="ph ph-plus-circle text-lg"></i> ¿Lo has visto? Añadir Pista
                </a>
            </div>

            <!-- Lista de Pistas Registradas -->
            @if($avistamientos->isEmpty())
                <div class="bg-amber-50/40 border-2 border-dashed border-amber-200 rounded-3xl p-8 text-center space-y-3">
                    <i class="ph ph-binoculars text-5xl text-amber-400"></i>
                    <h3 class="text-lg font-bold text-amber-950">Aún no hay pistas reportadas</h3>
                    <p class="text-xs text-amber-800 max-w-md mx-auto">
                        Si has visto a esta mascota o tienes cualquier detalle sobre su paradero, déjale una pista al dueño seleccionando el punto en el mapa a continuación.
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($avistamientos as $avistamiento)
                        <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-sm flex flex-col sm:flex-row gap-5">
                            @if($avistamiento->foto)
                                <div class="w-full sm:w-36 h-36 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                    @php
                                        $fotoAv = Str::startsWith($avistamiento->foto, 'http') ? $avistamiento->foto : asset('storage/' . $avistamiento->foto);
                                    @endphp
                                    <img src="{{ $fotoAv }}" alt="Foto avistamiento" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="flex-1 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($avistamiento->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800 capitalize">{{ $avistamiento->user->name ?? 'Usuario de la comunidad' }}</h4>
                                            <span class="text-[10px] text-slate-400 block">{{ \Carbon\Carbon::parse($avistamiento->fecha)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs bg-amber-50 text-amber-800 font-bold px-3 py-1 rounded-full border border-amber-100 flex items-center gap-1">
                                        <i class="ph ph-map-pin text-amber-600"></i> {{ $avistamiento->ubicacion_lat }}
                                    </span>
                                </div>

                                <p class="text-sm text-slate-700 italic bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    "{{ $avistamiento->descripcion }}"
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Formulario Integrado con Mapa Interactivo para Agregar una Pista -->
            <div id="form-reportar-avistamiento" class="bg-amber-50/40 rounded-3xl border border-amber-200 p-6 md:p-8 space-y-6 mt-8">
                <div class="flex items-center gap-3">
                    <div class="bg-amber-500 text-white p-2.5 rounded-xl">
                        <i class="ph ph-map-pin-line text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-amber-950">Añadir una Pista con Ubicación en Mapa</h3>
                        <p class="text-xs text-amber-800">Haz clic en el mapa para indicar dónde viste a esta mascota</p>
                    </div>
                </div>

                <form action="{{ route('avistamientos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() ?? 1 }}">
                    <input type="hidden" name="mascota_id" value="{{ $reporte->mascota_id }}">
                    <input type="hidden" name="redirect_to_extraviados" value="1">

                    <!-- Selección de Fecha y Búsqueda de Ubicación -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="sighting_fecha" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Fecha del Avistamiento <span class="text-amber-600">*</span>
                            </label>
                            <input type="date" name="fecha" id="sighting_fecha" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 rounded-xl border border-amber-200 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        </div>

                        <!-- Buscador rápido en el Mapa -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Buscar Colonia / Calle
                            </label>
                            <div class="flex gap-2">
                                <input type="text" id="sighting-search-input" placeholder="Ej. Calle 6, Col. Jardines, Matamoros..." class="w-full px-4 py-3 rounded-xl border border-amber-200 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                                <button type="button" id="btn-sighting-search" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-3 rounded-xl text-xs whitespace-nowrap">
                                    Buscar
                                </button>
                                <button type="button" id="btn-sighting-gps" title="Mi ubicación actual" class="bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold px-3 py-3 rounded-xl text-xs whitespace-nowrap">
                                    <i class="ph ph-crosshair text-base"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Mapa Interactivo Leaflet -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Punto exacto en el mapa <span class="text-amber-600">*</span>
                        </label>
                        <div id="sighting-osm-map" class="w-full h-64 rounded-2xl border border-amber-200 shadow-sm overflow-hidden z-0"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="ubicacion_lat" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Ubicación / Dirección Detectada <span class="text-amber-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lat" id="ubicacion_lat" required placeholder="Se actualiza al tocar el mapa..." class="w-full px-4 py-3 rounded-xl border border-amber-200 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        </div>

                        <div class="space-y-2">
                            <label for="ubicacion_lng" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Coordenadas del Punto <span class="text-amber-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lng" id="ubicacion_lng" required value="25.8690, -97.5027" class="w-full px-4 py-3 rounded-xl border border-amber-200 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        </div>
                    </div>

                    <!-- Ayudante rápido de Observaciones -->
                    <div class="space-y-2">
                        <label for="quick_status" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Estado o Comportamiento del Animal
                        </label>
                        <select id="quick_status" class="w-full px-4 py-3 rounded-xl border border-amber-200 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                            <option value="">-- Seleccionar estado visible (Opcional) --</option>
                            <option value="Desorientado / Asustado">Desorientado / Asustado</option>
                            <option value="Amigable / Juguetón">Amigable / Juguetón</option>
                            <option value="Parece lastimado">Parece lastimado</option>
                            <option value="Lo resguardé temporalmente en mi domicilio">Lo resguardé temporalmente en mi casa</option>
                            <option value="Se dirigía hacia el norte/sur de la colonia">Caminando desorientado por la zona</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="descripcion" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Detalles de la Pista / Avistamiento <span class="text-amber-600">*</span>
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="3" required placeholder="Describe hacia dónde caminaba, el estado en que lo viste o cualquier detalle útil para el dueño..." class="w-full px-4 py-3.5 rounded-xl border border-amber-200 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 leading-relaxed"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label for="foto" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Fotografía del Avistamiento <span class="text-slate-400 font-normal">(Opcional)</span>
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="w-full bg-white border border-amber-200 p-2.5 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-white hover:file:bg-amber-600 cursor-pointer transition-all">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 px-8 rounded-full shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                            <i class="ph ph-paper-plane-tilt text-xl"></i> Publicar Pista en la Alerta
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>

<!-- Script de Leaflet para los Mapas -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Mapa 1: Lugar donde se extravió la mascota
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

        var mapShow = L.map('show-osm-map').setView([defaultLat, defaultLng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapShow);

        L.marker([defaultLat, defaultLng]).addTo(mapShow)
            .bindPopup("<b>{{ $reporte->mascota->nombre }}</b><br>Lugar de extravío: {{ $reporte->ubicacion_lat }}")
            .openPopup();

        // 2. Mapa 2: Selección interactiva de ubicación para añadir una Pista
        var inputLat = document.getElementById('ubicacion_lat');
        var inputLng = document.getElementById('ubicacion_lng');
        var searchInput = document.getElementById('sighting-search-input');
        var searchBtn = document.getElementById('btn-sighting-search');
        var gpsBtn = document.getElementById('btn-sighting-gps');

        var mapSighting = L.map('sighting-osm-map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapSighting);

        var sightingMarker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(mapSighting);

        function updateSightingInputs(lat, lng, addressName) {
            if (addressName) {
                inputLat.value = addressName;
            }
            inputLng.value = lat.toFixed(6) + ', ' + lng.toFixed(6);
        }

        function reverseGeocodeSighting(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=es`)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var displayName = data.display_name || '';
                    var parts = displayName.split(',');
                    var shortAddress = parts.slice(0, 3).join(',').trim();
                    updateSightingInputs(lat, lng, shortAddress);
                });
        }

        sightingMarker.on('dragend', function (e) {
            var pos = sightingMarker.getLatLng();
            reverseGeocodeSighting(pos.lat, pos.lng);
        });

        mapSighting.on('click', function (e) {
            sightingMarker.setLatLng(e.latlng);
            reverseGeocodeSighting(e.latlng.lat, e.latlng.lng);
        });

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
                            mapSighting.setView([lat, lon], 15);
                            sightingMarker.setLatLng([lat, lon]);
                            updateSightingInputs(lat, lon, data[0].display_name.split(',').slice(0, 3).join(',').trim());
                        }
                    });
            });
        }

        if (gpsBtn) {
            gpsBtn.addEventListener('click', function () {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        mapSighting.setView([lat, lng], 15);
                        sightingMarker.setLatLng([lat, lng]);
                        reverseGeocodeSighting(lat, lng);
                    });
                }
            });
        }

        // Auto-completado de sugerencias de descripción
        var quickStatus = document.getElementById('quick_status');
        var textareaDesc = document.getElementById('descripcion');
        if (quickStatus && textareaDesc) {
            quickStatus.addEventListener('change', function () {
                var currentText = textareaDesc.value;
                // Elimina cualquier prefijo de "Estado visible: ..." anterior
                currentText = currentText.replace(/^Estado visible:\s*[^.]+\.\s*/i, '');

                if (this.value) {
                    textareaDesc.value = "Estado visible: " + this.value + ". " + currentText;
                } else {
                    textareaDesc.value = currentText;
                }
            });
        }
    });
</script>

@endsection
