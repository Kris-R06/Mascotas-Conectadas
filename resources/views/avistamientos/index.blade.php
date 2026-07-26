@extends('layout.admin')
@section('content')

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-4xl font-extrabold text-blue-900 flex items-center gap-3">
                <i class="ph ph-binoculars text-blue-500"></i> 
                Avistamientos Recientes
            </h1>
            <p class="mt-2 text-slate-500 font-light">
                Pistas y reportes ciudadanos. Ayuda a confirmar si estos peluditos son los que estamos buscando.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('avistamientos.index', ['filter' => request('filter') === 'mis_publicaciones' ? null : 'mis_publicaciones']) }}" class="{{ request('filter') === 'mis_publicaciones' ? 'bg-blue-950 text-white shadow-md' : 'bg-blue-50 hover:bg-blue-100 text-blue-700' }} font-bold py-3 px-6 rounded-full transition-all flex items-center gap-2 whitespace-nowrap text-sm">
                <i class="ph ph-user text-lg"></i> {{ request('filter') === 'mis_publicaciones' ? 'Ver Todos los Avistamientos' : 'Mis Publicaciones' }}
            </a>
            <a href="{{ route('avistamientos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2 whitespace-nowrap">
                <i class="ph ph-map-pin-line text-xl"></i> Reportar Avistamiento
            </a>
        </div>
    </div>

    <div class="w-full">
        
        @if($reportes->isEmpty())
            <div class="w-full max-w-4xl mx-auto mt-8 flex flex-col items-center justify-center bg-blue-50/50 border-2 border-dashed border-blue-200 py-16 px-10 rounded-3xl text-center">
                <i class="ph ph-map-trifold text-6xl mb-4 text-blue-400"></i>
                <h3 class="text-2xl font-bold mb-2 text-blue-800">
                    {{ request('filter') === 'mis_publicaciones' ? 'No tienes avistamientos reportados' : 'Sin reportes ciudadanos' }}
                </h3>
                <p class="text-blue-700/80 max-w-md">
                    {{ request('filter') === 'mis_publicaciones' ? 'Aún no has registrado ningún avistamiento con tu usuario.' : 'Aún no hay avistamientos registrados en tu zona. ¡Mantén los ojos abiertos!' }}
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                
                @foreach($reportes as $reporte)
                    <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-blue-50 flex flex-col relative group">
                        
                        @if($reporte->mascota)
                            <div class="absolute top-4 right-4 z-10 bg-emerald-500 text-white text-[10px] uppercase tracking-wider font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                                <i class="ph ph-check-circle"></i> Pista Vinculada
                            </div>
                        @else
                            <div class="absolute top-4 right-4 z-10 bg-blue-500 text-white text-[10px] uppercase tracking-wider font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                                <i class="ph ph-question"></i> Desconocido
                            </div>
                        @endif

                        <div class="relative h-56 w-full bg-slate-50 overflow-hidden">
                            @if($reporte->foto)
                                <img src="{{ asset('storage/' . $reporte->foto) }}" alt="Foto del avistamiento" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-blue-300 bg-blue-50/30">
                                    <i class="ph ph-camera-slash text-5xl mb-2"></i>
                                    <span class="text-xs">Sin imagen</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            
                            @if($reporte->mascota)
                                <p class="text-xs font-bold text-emerald-600 mb-1 flex items-center gap-1">
                                    Posible avistamiento de: <span class="capitalize">{{ $reporte->mascota->nombre }}</span>
                                </p>
                            @else
                                <p class="text-xs font-bold text-blue-600 mb-1 flex items-center gap-1">
                                    Mascota sin identificar
                                </p>
                            @endif
                            
                            <p class="text-xs text-slate-400 mb-4 flex items-center gap-1">
                                <i class="ph ph-clock text-blue-400"></i> Visto el {{ \Carbon\Carbon::parse($reporte->fecha)->format('d M, Y') }}
                            </p>
                            
                            <div class="bg-blue-50/40 p-3 rounded-xl border border-blue-100/50 mb-6 flex-grow">
                                <p class="text-sm text-slate-600 line-clamp-3 italic">
                                    "{{ $reporte->descripcion ?? 'Sin detalles adicionales.' }}"
                                </p>
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center gap-2">
                                @if($reporte->user_id == auth()->id())
                                    <a href="{{ route('avistamientos.edit', $reporte->id) }}" class="flex-1 flex justify-center items-center gap-1.5 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-2.5 px-3 rounded-xl transition-colors text-xs">
                                        <i class="ph ph-pencil-simple text-base"></i> Editar
                                    </a>
                                    <form action="{{ route('avistamientos.destroy', $reporte->id) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este avistamiento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 font-bold p-2.5 rounded-xl transition-colors text-xs" title="Eliminar Avistamiento">
                                            <i class="ph ph-trash text-base"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="#" class="w-full flex justify-center items-center gap-2 bg-blue-50 hover:bg-blue-500 hover:text-white text-blue-700 font-bold py-2.5 px-4 rounded-xl transition-colors text-sm">
                                        <i class="ph ph-magnifying-glass text-lg"></i> Analizar pista
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection