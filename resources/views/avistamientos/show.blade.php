@extends('layout.admin')

@section('content')

<!-- Estilos Leaflet para OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <!-- Encabezado con Botón de Regresar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-100 pb-6 max-w-6xl mx-auto">
        <a href="{{ route('avistamientos.index') }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-2.5 px-5 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 text-sm">
            <i class="ph ph-arrow-left text-lg"></i> Regresar a Avistamientos
        </a>

        @if(auth()->id() == $reporte->user_id)
            <div class="flex items-center gap-3">
                <a href="{{ route('avistamientos.edit', $reporte->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-full shadow-sm hover:shadow-md transition-all flex items-center gap-2 text-sm">
                    <i class="ph ph-pencil-simple text-lg"></i> Editar Mi Avistamiento
                </a>
            </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <div class="w-full max-w-6xl mx-auto space-y-10">

        <!-- Ficha de Avistamiento / Pista -->
        <div class="bg-white rounded-3xl shadow-sm border border-amber-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12 gap-0">
            
            <!-- Foto del Avistamiento -->
            <div class="lg:col-span-5 relative bg-slate-100 h-80 lg:h-auto min-h-[320px]">
                <img src="{{ $reporte->foto_url }}" alt="Fotografía del Avistamiento" class="w-full h-full object-cover">
                <div class="absolute top-4 left-4 z-10 bg-amber-500 text-white text-xs uppercase tracking-wider font-extrabold px-4 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
                    <i class="ph ph-binoculars text-lg"></i> Pista de Avistamiento
                </div>
                @if($reporte->mascota_id && $reporte->mascota)
                    <div class="absolute bottom-4 left-4 right-4 z-10 bg-emerald-600/95 text-white backdrop-blur-md text-xs font-bold p-3 rounded-2xl shadow-lg flex items-center gap-2">
                        <i class="ph ph-check-circle text-lg"></i>
                        <span>Posible coincidencia con: <strong>{{ $reporte->mascota->nombre }}</strong></span>
                    </div>
                @endif
            </div>

            <!-- Información Detallada del Avistamiento -->
            <div class="lg:col-span-7 p-6 md:p-8 flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs uppercase tracking-wider font-extrabold text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
                                Avistamiento Ciudadano
                            </span>
                            <h1 class="text-3xl font-extrabold text-blue-950 mt-2">
                                @if($reporte->mascota_id && $reporte->mascota)
                                    Avistamiento de {{ $reporte->mascota->nombre }}
                                @else
                                    Mascota Sin Identificar
                                @endif
                            </h1>
                        </div>
                        <span class="text-xs text-slate-400 font-semibold flex items-center gap-1">
                            <i class="ph ph-clock text-amber-500"></i> Reportado el {{ \Carbon\Carbon::parse($reporte->fecha)->format('d \d\e M, Y') }}
                        </span>
                    </div>

                    <!-- Cuadrícula de Datos Clave -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6">
                        <div class="bg-amber-50/50 p-3 rounded-2xl border border-amber-100/60">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Zona / Ubicación</span>
                            <strong class="text-sm text-slate-800 capitalize truncate block" title="{{ $reporte->ubicacion_lat }}">
                                {{ $reporte->ubicacion_lat ?? 'Zona no especificada' }}
                            </strong>
                        </div>
                        <div class="bg-amber-50/50 p-3 rounded-2xl border border-amber-100/60">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Especie</span>
                            <strong class="text-sm text-slate-800 capitalize">
                                {{ $reporte->mascota->especie->nombre ?? 'Animal' }}
                            </strong>
                        </div>
                        <div class="bg-amber-50/50 p-3 rounded-2xl border border-amber-100/60">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Reportado por</span>
                            <strong class="text-sm text-slate-800 capitalize">
                                {{ $reporte->user->name ?? 'Usuario de la comunidad' }}
                            </strong>
                        </div>
                    </div>

                    <!-- Descripción y Señas Particulares del Avistamiento -->
                    <div class="mt-6 space-y-2">
                        <h3 class="text-xs uppercase tracking-wider font-bold text-slate-500">Detalles de la Pista / Comportamiento Observado</h3>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-sm text-slate-700 leading-relaxed italic">
                            "{{ $reporte->descripcion ?? 'Sin descripción adicional detallada.' }}"
                        </div>
                    </div>
                </div>

                <!-- Opciones de Contacto con el Usuario que Reportó -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row gap-3 items-center">
                    @if($reporte->user && $reporte->user->telefono)
                        <a href="tel:{{ $reporte->user->telefono }}" class="w-full sm:w-auto flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-5 rounded-2xl transition-all shadow flex items-center justify-center gap-2 text-sm">
                            <i class="ph ph-phone text-xl"></i> Llamar al Informante ({{ $reporte->user->telefono }})
                        </a>
                        <a href="https://wa.me/52{{ preg_replace('/[^0-9]/', '', $reporte->user->telefono) }}?text=Hola,%20vi%20tu%20publicación%20de%20avistamiento%20en%20Mascotas%20Conectadas." target="_blank" class="w-full sm:w-auto bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold py-3 px-5 rounded-2xl transition-all border border-emerald-200 flex items-center justify-center gap-2 text-sm">
                            <i class="ph ph-whatsapp-logo text-xl text-emerald-600"></i> WhatsApp
                        </a>
                    @else
                        <div class="text-xs text-slate-400 italic flex items-center gap-1">
                            <i class="ph ph-info text-slate-400 text-base"></i>
                            El usuario que registró este avistamiento no proporcionó un teléfono directo.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mapa interactivo del Punto de Avistamiento -->
        <div class="bg-white rounded-3xl shadow-sm border border-amber-100 p-6 space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600">
                        <i class="ph ph-map-pin text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-blue-950">Mapa del Avistamiento</h2>
                        <p class="text-xs text-slate-400">Punto exacto reportado por la comunidad</p>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 text-xs font-bold text-amber-700 bg-amber-50 p-2.5 rounded-xl border border-amber-100">
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block shadow-sm"></span> Lugar donde fue visto
                </div>
            </div>

            <div id="show-avistamiento-map" class="w-full h-80 rounded-2xl border border-amber-100 shadow-inner overflow-hidden z-0"></div>
        </div>

    </div>
</div>

<!-- Script Leaflet OpenStreetMap -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rawLngStr = "{{ $reporte->ubicacion_lng }}";
        var defaultLat = 25.8690;
        var defaultLng = -97.5027;

        var coords = rawLngStr.split(',');
        if (coords.length === 2 && !isNaN(parseFloat(coords[0])) && !isNaN(parseFloat(coords[1]))) {
            defaultLat = parseFloat(coords[0].trim());
            defaultLng = parseFloat(coords[1].trim());
        }

        var map = L.map('show-avistamiento-map').setView([defaultLat, defaultLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var amberIcon = L.divIcon({
            className: 'custom-leaflet-marker',
            html: '<div style="background-color: #f59e0b; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3);"></div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        L.marker([defaultLat, defaultLng], { icon: amberIcon })
            .addTo(map)
            .bindPopup("<b>Avistamiento reportado aquí</b><br>{{ $reporte->ubicacion_lat }}")
            .openPopup();

        setTimeout(function() { map.invalidateSize(); }, 250);
    });
</script>

@endsection
