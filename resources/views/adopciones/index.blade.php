@extends('layout.admin')
@section('content')

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans overflow-y-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-4xl font-extrabold text-blue-950 flex items-center gap-3">
                <i class="ph ph-paw-print text-blue-500 animate-pulse"></i> 
                Adopciones
            </h1>
            <p class="mt-2 text-slate-500 font-light">
                Explora las oportunidades de adopción disponibles en la comunidad.
            </p>
        </div>
        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2 whitespace-nowrap">
            <i class="ph ph-paw-print text-xl"></i> Agregar adopciones
        </a>
    </div>

    <div class="w-full">
        @php $hayAdopciones = false; @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
            
            @foreach($adopciones as $adopcion)
                @php $hayAdopciones = true; @endphp
                @if($adopcion->mascota && $adopcion->estatus === 'pendiente')
                    
                    <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-blue-50 flex flex-col relative group">
                        
                        <div class="absolute top-4 right-4 z-10 bg-blue-600 text-white text-[10px] uppercase tracking-wider font-bold px-3 py-1.5 rounded-full shadow-lg">
                            En adopción
                        </div>

                        <div class="relative h-56 w-full bg-slate-50 overflow-hidden">
                            @if($adopcion->mascota->foto)
                                <img src="{{ asset('storage/' . $adopcion->mascota->foto) }}" alt="{{ $adopcion->mascota->nombre }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-blue-300 bg-blue-50/30">
                                    <i class="ph ph-image-broken text-5xl mb-2"></i>
                                    <span class="text-xs">Sin imagen</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <h2 class="text-xl font-bold text-blue-950 mb-1 capitalize">{{ $adopcion->mascota->nombre }}</h2>
                            
                            <p class="text-xs text-slate-400 mb-4 flex items-center gap-1">
                                <i class="ph ph-clock text-blue-400"></i> {{ \Carbon\Carbon::parse($adopcion->fecha)->format('d M, Y') }}
                            </p>
                            
                            <div class="flex flex-col gap-2 mb-6 text-sm">
                                <div class="flex items-center gap-3 bg-blue-50/50 p-2 rounded-xl border border-blue-100/50">
                                    <div class="bg-blue-100 p-1.5 rounded-lg text-blue-600">
                                        <i class="ph ph-paw-print text-lg"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Especie</span>
                                        <span class="font-semibold text-slate-700 capitalize leading-tight">{{ $adopcion->mascota->especie->nombre ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 bg-blue-50/50 p-2 rounded-xl border border-blue-100/50">
                                    <div class="bg-blue-100 p-1.5 rounded-lg text-blue-600">
                                        <i class="ph ph-tag text-lg"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Raza</span>
                                        <span class="font-semibold text-slate-700 capitalize leading-tight">{{ $adopcion->mascota->raza ?? 'Mestizo' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-50">
                                <a href="#" class="w-full flex justify-center items-center gap-2 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold py-2.5 px-4 rounded-xl transition-colors text-sm">
                                    <i class="ph ph-eye text-lg"></i> Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if(!$hayAdopciones)
            <div class="w-full max-w-4xl mx-auto mt-8 flex flex-col items-center justify-center bg-blue-50/40 border-2 border-dashed border-blue-300 py-16 px-10 rounded-3xl text-center">
                <i class="ph ph-paw-print text-6xl mb-4 text-blue-500"></i>
                <h3 class="text-2xl font-bold mb-2 text-blue-900">¡Pronto habrá más oportunidades de adopción!</h3>
                <p class="text-blue-700/80 max-w-md">
                    Actualmente no hay adopciones disponibles en este momento, pero nuevas mascotas pronto podrán encontrar un hogar.
                </p>
            </div>
        @endif
    </div>

</div>

@endsection