@extends('layout.admin')

@section('content')

<!-- Estilos Leaflet para OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-4xl font-extrabold text-blue-950 flex items-center gap-3">
                <i class="ph ph-pencil-simple text-amber-500"></i> 
                Editar Avistamiento
            </h1>
            <p class="mt-2 text-slate-500 font-light">
                Modifica la ubicación, descripción o fotografía del avistamiento reportado.
            </p>
        </div>
        <a href="{{ route('avistamientos.index') }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-3 px-6 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 whitespace-nowrap text-sm">
            <i class="ph ph-arrow-left text-xl"></i> Regresar a Avistamientos
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

            <form action="{{ route('avistamientos.update', $reporte->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Sección 1: Selección de Mascota / Datos Básicos -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600">
                            <i class="ph ph-paw-print text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-950">Información del Avistamiento</h2>
                            <p class="text-xs text-slate-400">Modifica si corresponde a un animal visto en la calle o a una mascota registrada</p>
                        </div>
                    </div>

                    @php
                        $isKnown = old('tipo_avistamiento', $reporte->mascota_id ? 'registrada' : 'desconocida') === 'registrada';
                    @endphp

                    <div class="bg-amber-50/30 p-5 rounded-2xl border border-amber-100/60 space-y-4">
                        <!-- Selector simple de tipo de reporte -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label id="card-type-unknown" class="flex items-center gap-3 p-4 rounded-xl border-2 {{ !$isKnown ? 'border-amber-500' : 'border-slate-200' }} bg-white cursor-pointer transition-all shadow-sm">
                                <input type="radio" name="tipo_avistamiento" value="desconocida" {{ !$isKnown ? 'checked' : '' }} class="accent-amber-500 w-4 h-4">
                                <div class="text-sm">
                                    <strong class="font-bold text-slate-800 block">Mascota sin identificar (En la calle)</strong>
                                    <span class="text-xs text-slate-500">Un animal visto en la calle de nombre desconocido.</span>
                                </div>
                            </label>

                            <label id="card-type-known" class="flex items-center gap-3 p-4 rounded-xl border-2 {{ $isKnown ? 'border-amber-500' : 'border-slate-200' }} bg-white hover:border-amber-300 cursor-pointer transition-all">
                                <input type="radio" name="tipo_avistamiento" value="registrada" {{ $isKnown ? 'checked' : '' }} class="accent-amber-500 w-4 h-4">
                                <div class="text-sm">
                                    <strong class="font-bold text-slate-800 block">Vincular a mascota registrada</strong>
                                    <span class="text-xs text-slate-500">Sé a qué reporte oficial corresponde esta vista.</span>
                                </div>
                            </label>
                        </div>

                        <!-- Opción A: Desplegable si se selecciona Mascota Registrada -->
                        <div id="container-mascota-select" class="{{ $isKnown ? '' : 'hidden' }} space-y-2 pt-2 border-t border-amber-100">
                            <label for="mascota_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Seleccionar Mascota de la Comunidad
                            </label>
                            <select name="mascota_id" id="mascota_id" class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm">
                                <option value="">-- Selecciona una mascota de la comunidad --</option>
                                @foreach($mascotas as $mascota)
                                    <option value="{{ $mascota->id }}" {{ old('mascota_id', $reporte->mascota_id) == $mascota->id ? 'selected' : '' }}>
                                        {{ $mascota->nombre }} — {{ ucfirst($mascota->especie->nombre ?? 'Animal') }} ({{ $mascota->raza ?? 'Mestizo' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Opción B: Campos guiados para describir mascota no identificada -->
                        <div id="container-unknown-fields" class="{{ $isKnown ? 'hidden' : '' }} space-y-4 pt-2">
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
                                <h2 class="text-lg font-bold text-blue-950">Ubicación donde fue visto</h2>
                                <p class="text-xs text-slate-400">Actualiza el punto del mapa</p>
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
                                <input type="text" id="map-search-input" autocomplete="off" placeholder="Buscar dirección..." class="w-full px-4 py-3 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
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
                            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', optional($reporte->fecha)->format('Y-m-d')) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="ubicacion_lat" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Ubicación / Colonia <span class="text-amber-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lat" id="ubicacion_lat" value="{{ old('ubicacion_lat', $reporte->ubicacion_lat) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="ubicacion_lng" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Referencia o Coordenadas <span class="text-amber-600">*</span>
                            </label>
                            <input type="text" name="ubicacion_lng" id="ubicacion_lng" value="{{ old('ubicacion_lng', $reporte->ubicacion_lng) }}" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm">
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
                            <p class="text-xs text-slate-400">Actualiza la foto o descripción del avistamiento</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @if($reporte->foto)
                            <div class="bg-amber-50/40 p-4 sm:p-5 rounded-2xl border border-amber-100/80 space-y-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Foto Actual del Avistamiento
                                </label>
                                <div class="flex items-center gap-4 bg-white p-3 rounded-xl border border-amber-100 shadow-sm">
                                    <div class="relative w-20 h-20 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                        @php
                                            $fotoAv = Str::startsWith($reporte->foto, 'http') ? $reporte->foto : asset('storage/' . $reporte->foto);
                                        @endphp
                                        <img src="{{ $fotoAv }}" alt="Foto Avistamiento" class="w-full h-full object-cover">
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="text-xs font-bold text-slate-800">Fotografía guardada</h4>
                                        <p class="text-[11px] text-slate-500">Se mantendrá esta imagen si no subes un archivo nuevo.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3 flex-1">
                            <label for="foto" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                                Cambiar Fotografía <span class="text-slate-400 font-normal">(Opcional)</span>
                            </label>
                            <input type="file" name="foto" id="foto" accept="image/*" class="w-full bg-white border border-slate-200 p-2.5 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 file:mr-4 file:py-2 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-white hover:file:bg-amber-600 cursor-pointer transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="descripcion" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Descripción / Detalles <span class="text-amber-600">*</span>
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="4" required class="w-full px-4 py-3.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-sm leading-relaxed">{{ old('descripcion', $reporte->descripcion) }}</textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('avistamientos.index') }}" class="w-full sm:w-auto text-center px-6 py-3 rounded-full text-slate-500 font-bold hover:bg-slate-100 transition-colors text-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 px-8 rounded-full shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="ph ph-check-circle text-xl"></i> Guardar Cambios del Avistamiento
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
        var selectMascota = document.getElementById('mascota_id');

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
                if (selectMascota) selectMascota.value = '';
            }
        }

        radioUnknown.addEventListener('change', updateTypeMode);
        radioKnown.addEventListener('change', updateTypeMode);

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
                textareaDesc.value = prefix + '. ' + textareaDesc.value;
            }
        }

        if (qEspecie) qEspecie.addEventListener('change', syncQuickDescription);
        if (qTamano) qTamano.addEventListener('change', syncQuickDescription);

        // OpenStreetMap + Nominatim + Geolocalización GPS
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

        var myLocationBtn = document.getElementById('btn-my-location');
        if (myLocationBtn) {
            myLocationBtn.addEventListener('click', getUserLocation);
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
