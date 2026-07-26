@extends('layout.admin')

@section('content') 
    <!-- Área de Contenido Principal -->
    <main class="flex-1 overflow-y-auto p-4 md:p-8 space-y-8 bg-slate-50/50">
        
        <!-- Hero Banner principal -->
        <section class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-blue-500/10 relative overflow-hidden">
            <!-- Patrón decorativo de fondo -->
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                <i class="ph-fill ph-paw-print text-[260px]"></i>
            </div>
            
            <div class="max-w-2xl relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold uppercase tracking-wider text-blue-50 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Red Comunitaria Activa
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Conectando mascotas con sus familias y nuevos hogares
                </h1>
                
                <p class="text-blue-100 text-base md:text-lg leading-relaxed">
                    Reporta extravíos en tiempo real, publica avistamientos en tu zona para ayudar a los dueños, o abre las puertas de tu hogar a una mascota en adopción.
                </p>

                <!-- Botones de Acción Rápida con Enlaces Laravel Directos -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="{{ route('reportes.create') }}" class="bg-rose-500 hover:bg-rose-600 text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-rose-900/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                        Reportar Extravío
                    </a>
                    
                    <a href="{{ route('reportes.create') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-amber-900/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="ph-bold ph-binoculars text-xl"></i>
                        Publicar Avistamiento
                    </a>
                    
                    <a href="{{ route('index') }}" class="bg-white/10 hover:bg-white/20 border border-white/30 text-white backdrop-blur-md px-5 py-3 rounded-xl font-semibold transition-all flex items-center gap-2">
                        <i class="ph-bold ph-heart text-xl"></i>
                        Explorar Adopciones
                    </a>
                </div>
            </div>
        </section>

        <!-- Tarjetas de Resumen (Widgets / Métricas Dinámicas) -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Widget: Extravíos -->
            <a href="{{ route('reportes.index') }}" class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm flex items-center justify-between hover:shadow-md transition-all group">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-rose-500">Alertas Activas</span>
                    <h3 class="text-2xl font-black text-slate-800">{{ $extravios_count ?? 0 }} Extravíos</h3>
                    <p class="text-xs text-slate-500 flex items-center gap-1">
                        <span class="text-rose-500 font-semibold">Casos activos</span> en la comunidad
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="ph-fill ph-warning-circle"></i>
                </div>
            </a>
            
            <!-- Widget: Avistamientos -->
            <a href="{{ route('reportes.index') }}" class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center justify-between hover:shadow-md transition-all group">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">Comunidad</span>
                    <h3 class="text-2xl font-black text-slate-800">{{ $avistamientos_count ?? 0 }} Avistamientos</h3>
                    <p class="text-xs text-slate-500">Pistas registradas por usuarios</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="ph-fill ph-binoculars"></i>
                </div>
            </a>

            <!-- Widget: Adopciones -->
            <a href="{{ route('index') }}" class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex items-center justify-between hover:shadow-md transition-all group">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-blue-600">Nuevos Hogares</span>
                    <h3 class="text-2xl font-black text-slate-800">{{ $adopciones_count ?? 0 }} En Adopción</h3>
                    <p class="text-xs text-slate-500">Listos para ser adoptados</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="ph-fill ph-heart"></i>
                </div>
            </a>
            
            <!-- Widget: Reencuentros -->
            <a href="{{ route('index') }}" class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex items-center justify-between hover:shadow-md transition-all group">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Éxito Comunitario</span>
                    <h3 class="text-2xl font-black text-slate-800">{{ $reencuentros_count ?? 0 }} Reencuentros</h3>
                    <p class="text-xs text-slate-500">Casos resueltos con éxito</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="ph-fill ph-check-circle"></i>
                </div>
            </a>
        </section>

        <!-- Barra de Búsqueda y Filtros Estándar HTML (GET) -->
        <form method="GET" action="{{ route('home') }}" class="bg-white p-4 rounded-2xl border border-blue-100/80 shadow-sm flex flex-col lg:flex-row gap-4 items-center justify-between">
            <!-- Input de Búsqueda -->
            <div class="relative w-full lg:w-96">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, raza o descripción..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>

            <!-- Filtro por Especie -->
            <div class="flex items-center gap-3 w-full lg:w-auto justify-end border-t lg:border-t-0 pt-3 lg:pt-0 border-slate-100">
                <span class="text-xs text-slate-500 font-semibold">Filtrar por Especie:</span>
                <select name="especie_id" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="">Todas las especies</option>
                    @foreach($especies as $esp)
                        <option value="{{ $esp->id }}" {{ request('especie_id') == $esp->id ? 'selected' : '' }}>
                            🐾 {{ $esp->nombre }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors">
                    Filtrar
                </button>
                @if(request('search') || request('especie_id'))
                    <a href="{{ route('home') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-medium hover:bg-slate-200 transition-colors">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>

        <!-- Grid Principal: Mascotas Extraviadas y Avistamientos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Columna Izquierda / Central: Feed de Extravíos Urgentes -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <i class="ph-fill ph-warning-circle text-rose-500"></i>
                            Extravíos Urgentes
                        </h2>
                        <p class="text-xs text-slate-500">Mascotas perdidas reportadas recientemente</p>
                    </div>
                    <a href="{{ route('reportes.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                        Ver todos los reportes <i class="ph-bold ph-caret-right"></i>
                    </a>
                </div>

                <!-- Lista Dinámica de Extravíos -->
                <div class="space-y-6">
                    @forelse($extravios as $reporte)
                        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
                            <div class="p-4 sm:p-6 space-y-4">
                                <!-- Header de la publicación -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-sm border border-blue-200">
                                            {{ strtoupper(substr($reporte->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800">{{ $reporte->user->name ?? 'Usuario de la comunidad' }}</h4>
                                            <p class="text-xs text-slate-400">
                                                Publicado {{ $reporte->created_at ? $reporte->created_at->diffForHumans() : 'Recientemente' }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200 text-xs font-extrabold flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                                        ¡SE BUSCA!
                                    </span>
                                </div>

                                <!-- Contenido principal de la mascota -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="relative sm:col-span-1 rounded-xl overflow-hidden aspect-square sm:aspect-auto bg-slate-100">
                                        @if($reporte->foto)
                                            <img src="{{ Str::startsWith($reporte->foto, 'http') ? $reporte->foto : asset('storage/' . $reporte->foto) }}" 
                                                 alt="{{ $reporte->mascota->nombre ?? 'Mascota' }}" 
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?auto=format&fit=crop&w=600&q=80';">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?auto=format&fit=crop&w=600&q=80" 
                                                 alt="Mascota Extraviada" 
                                                 class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    
                                    <div class="sm:col-span-2 space-y-3 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <h3 class="text-lg font-black text-slate-900">
                                                    {{ $reporte->mascota->nombre ?? 'Mascota perdida' }} 
                                                    @if(isset($reporte->mascota->raza))
                                                        <span class="text-slate-500 font-normal">({{ $reporte->mascota->raza }})</span>
                                                    @endif
                                                </h3>
                                                @if(isset($reporte->mascota->edad))
                                                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">
                                                        {{ $reporte->mascota->edad }} años
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-600 leading-relaxed">
                                                {{ $reporte->descripcion }}
                                            </p>
                                        </div>

                                        <!-- Detalles clave -->
                                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                            <div class="flex items-center gap-1.5 text-slate-600 truncate">
                                                <i class="ph-bold ph-map-pin text-rose-500 flex-shrink-0"></i>
                                                <span class="truncate">Ubicación: {{ $reporte->ubicacion_lat ?? 'Registrada' }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-slate-600">
                                                <i class="ph-bold ph-paw-print text-blue-500 flex-shrink-0"></i>
                                                <span>Especie: {{ $reporte->mascota->especie->nombre ?? 'Animal' }}</span>
                                            </div>
                                        </div>

                                        <!-- Botones de Acción directos con enlaces a la vista de reportes -->
                                        <div class="flex flex-wrap items-center gap-2 pt-1">
                                            <a href="{{ route('reportes.create') }}" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-2 px-3 rounded-xl font-bold text-xs shadow-sm transition-colors flex items-center justify-center gap-1.5">
                                                <i class="ph-bold ph-binoculars"></i>
                                                Reportar Avistamiento
                                            </a>
                                            <a href="{{ route('reportes.index') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 px-3 rounded-xl font-bold text-xs transition-colors flex items-center gap-1.5">
                                                <i class="ph-bold ph-eye text-base"></i>
                                                <span>Ver Detalle</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Estado Vacío cuando no hay extravíos en DB -->
                        <div class="bg-white rounded-2xl p-8 text-center border border-slate-200/80 space-y-3">
                            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-3xl mx-auto">
                                <i class="ph-fill ph-paw-print"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">No hay reportes de extravío activos</h3>
                            <p class="text-xs text-slate-500 max-w-md mx-auto">
                                ¡Buenas noticias! No se han registrado mascotas perdidas en este momento o que coincidan con la búsqueda.
                            </p>
                            <a href="{{ route('reportes.create') }}" class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors shadow">
                                <i class="ph-bold ph-plus"></i> Publicar Reporte de Extravío
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Columna Derecha: Feed Lateral de Avistamientos -->
            <div class="space-y-6">
                
                <!-- Panel: Avistamientos Recientes -->
                <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="ph-fill ph-binoculars text-amber-500 text-lg"></i>
                            Últimos Avistamientos
                        </h3>
                        <span class="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">En tiempo real</span>
                    </div>
                    <p class="text-xs text-slate-500">Mascotas vistas por la comunidad.</p>

                    <div class="space-y-3 divide-y divide-slate-100">
                        @forelse($avistamientos as $avistamiento)
                            <div class="pt-3 first:pt-0 space-y-2">
                                <div class="flex items-start justify-between">
                                    <span class="text-xs font-bold text-slate-800">
                                        {{ $avistamiento->mascota->nombre ?? 'Mascota Avistada' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $avistamiento->created_at ? $avistamiento->created_at->diffForHumans() : 'Reciente' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2">
                                    {{ $avistamiento->descripcion }}
                                </p>
                                <div class="flex items-center justify-between text-[11px] pt-1">
                                    <span class="text-slate-400 flex items-center gap-1 truncate max-w-[180px]">
                                        <i class="ph-bold ph-map-pin text-amber-500 flex-shrink-0"></i> 
                                        {{ $avistamiento->ubicacion_lat ?? 'Zona reportada' }}
                                    </span>
                                    <a href="{{ route('reportes.index') }}" class="text-blue-600 font-bold hover:underline flex-shrink-0">Ver Detalle</a>
                                </div>
                            </div>
                        @empty
                            <div class="py-4 text-center text-xs text-slate-400">
                                No se han registrado avistamientos hoy.
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('reportes.create') }}" class="w-full mt-2 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-xl border border-amber-200 transition-colors flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-plus-circle"></i>
                        Registrar Nuevo Avistamiento
                    </a>
                </div>

                <!-- Consejos Rápidos -->
                <div class="bg-gradient-to-br from-blue-900 to-slate-900 text-white p-5 rounded-2xl shadow-sm space-y-3 relative overflow-hidden">
                    <i class="ph-fill ph-lightbulb text-6xl text-amber-400/20 absolute -right-2 -bottom-2 pointer-events-none"></i>
                    <h4 class="text-sm font-bold flex items-center gap-2 text-amber-400">
                        <i class="ph-bold ph-lightbulb"></i>
                        ¿Qué hacer si perdiste a tu mascota?
                    </h4>
                    <ul class="text-xs text-slate-300 space-y-2 list-disc list-inside">
                        <li>Publica el reporte de inmediato con foto clara.</li>
                        <li>Revisa constantemente la lista de <strong>avistamientos</strong>.</li>
                        <li>Notifica a los vecinos de tu colonia.</li>
                    </ul>
                </div>

            </div>

        </div>

        <!-- Sección: Mascotas en Adopción Dinámicas -->
        <section id="adopciones-section" class="space-y-6 pt-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                        <i class="ph-fill ph-heart text-rose-500"></i>
                        Mascotas Buscando Hogar
                    </h2>
                    <p class="text-xs text-slate-500">Conoce a las mascotas listas para ser adoptadas</p>
                </div>
                <a href="{{ route('index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    Ver catálogo completo <i class="ph-bold ph-caret-right"></i>
                </a>
            </div>

            <!-- Grid Dinámico de Adopciones -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($mascotasAdopcion as $mascota)
                    <div class="bg-white rounded-2xl border border-blue-100/80 shadow-sm overflow-hidden hover:shadow-lg transition-all group flex flex-col justify-between">
                        <div>
                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                @if($mascota->foto)
                                    <img src="{{ Str::startsWith($mascota->foto, 'http') ? $mascota->foto : asset('storage/' . $mascota->foto) }}" 
                                         alt="{{ $mascota->nombre }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=600&q=80';">
                                @else
                                    <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=600&q=80" 
                                         alt="{{ $mascota->nombre }}" 
                                         class="w-full h-full object-cover">
                                @endif
                                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-bold text-slate-700 shadow">
                                    🐾 {{ $mascota->especie->nombre ?? 'Mascota' }}
                                </span>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-slate-800">{{ $mascota->nombre }}</h3>
                                    <span class="text-xs font-medium text-slate-500">{{ $mascota->edad }} {{ $mascota->edad == 1 ? 'año' : 'años' }}</span>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2">
                                    {{ $mascota->descripcion }}
                                </p>
                                <div class="flex flex-wrap gap-1">
                                    <span class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded-md font-semibold">Raza: {{ $mascota->raza }}</span>
                                    @if($mascota->kid_friendly)
                                        <span class="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md font-semibold">Apto para niños</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="p-4 pt-0">
                            <a href="{{ route('create', ['mascota_id' => $mascota->id]) }}" class="w-full py-2 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5">
                                <i class="ph-bold ph-heart"></i>
                                Solicitar Adopción
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl p-8 text-center border border-slate-200 space-y-3">
                        <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-2xl mx-auto">
                            <i class="ph-fill ph-heart"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">No hay mascotas en adopción disponibles por ahora</h3>
                        <p class="text-xs text-slate-500">Pronto se registrarán más amiguitos buscando hogar.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
    
@endsection