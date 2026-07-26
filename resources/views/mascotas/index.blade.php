@extends('layout.admin')

@section('content')
<div class="w-full flex-grow bg-slate-50 p-6 md:p-10 font-sans">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 flex items-center gap-3">
                <i class="ph ph-paw-print text-indigo-500"></i> Mis Mascotas
            </h1>
            <p class="mt-2 text-slate-500 font-light">
                Administra los perfiles de tus peludos, genera sus placas QR o gestiónalos para adopción.
            </p>
        </div>
        <a href="{{ route('mascotas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2 whitespace-nowrap">
            <i class="ph ph-plus-circle text-xl"></i> Registrar Mascota
        </a>
    </div>

    <div class="w-full">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
            
            {{-- Iteramos sobre las mascotas del usuario --}}
            @forelse($mascotas as $mascota)
                <div class="relative bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100 flex flex-col group">
                    
                    @if($mascota->estatus === 'extraviado')
                        <div class="absolute top-4 right-4 z-10 bg-red-500 text-white text-[10px] uppercase tracking-wider font-bold px-3 py-1.5 rounded-full shadow-lg">
                            Extraviado
                        </div>
                    @elseif($mascota->estatus === 'adopcion')
                        <div class="absolute top-4 right-4 z-10 bg-emerald-500 text-white text-[10px] uppercase tracking-wider font-bold px-3 py-1.5 rounded-full shadow-lg">
                            En Adopción
                        </div>
                    @endif

                    <div class="relative h-56 w-full bg-slate-100 overflow-hidden">
                        @if($mascota->foto)
                            <img src="{{ asset('storage/' . $mascota->foto) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                <i class="ph ph-image-broken text-5xl mb-2"></i>
                                <span class="text-xs">Sin foto</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <h2 class="text-2xl font-bold text-slate-800 mb-4 capitalize">{{ $mascota->nombre }}</h2>
                        
                        <div class="flex flex-col gap-2 mb-6 text-sm">
                            
                            <div class="flex items-center gap-3 bg-indigo-50/50 p-2 rounded-xl border border-indigo-100/50">
                                <div class="bg-indigo-100 p-1.5 rounded-lg text-indigo-600">
                                    <i class="ph ph-cat text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Especie</span>
                                    <span class="font-semibold text-slate-700 capitalize leading-tight">{{ $mascota->especie->nombre ?? 'Desconocida' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 bg-indigo-50/50 p-2 rounded-xl border border-indigo-100/50">
                                <div class="bg-indigo-100 p-1.5 rounded-lg text-indigo-600">
                                    <i class="ph ph-tag text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Raza</span>
                                    <span class="font-semibold text-slate-700 capitalize leading-tight">{{ $mascota->raza ?? 'Mestizo' }}</span>
                                </div>
                            </div>
                            
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-50 flex gap-2">
                            <a href="{{ route('mascotas.edit', $mascota->id) }}" class="flex-1 flex justify-center items-center gap-2 bg-slate-50 hover:bg-indigo-500 hover:text-white text-indigo-600 font-bold py-2.5 rounded-xl transition-colors text-sm">
                                <i class="ph ph-pencil-simple text-lg"></i> Editar
                            </a>
                            <a href="{{ route('mascotas.show', $mascota->id) }}" class="flex-1 flex justify-center items-center gap-2 bg-slate-50 hover:bg-indigo-500 hover:text-white text-indigo-600 font-bold py-2.5 rounded-xl transition-colors text-sm">
                                <i class="ph ph-eye text-lg"></i> Ver
                            </a>
                        </div>
                    </div>
                </div>
            
            {{-- Estado vacío si no ha registrado mascotas --}}
            @empty
                <div class="col-span-full w-full max-w-4xl mx-auto mt-8 flex flex-col items-center justify-center bg-indigo-50/40 border-2 border-dashed border-indigo-200 py-16 px-10 rounded-3xl text-center">
                    <i class="ph ph-dog text-6xl mb-4 text-indigo-400"></i>
                    <h3 class="text-2xl font-bold mb-2 text-indigo-900">Aún no tienes mascotas registradas</h3>
                    <p class="text-indigo-700/80 max-w-md mb-6">
                        Agrega a tus peludos al sistema para generar su código QR, crear reportes de extravío si es necesario, o darlos en adopción.
                    </p>
                    <a href="{{ route('mascotas.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2.5 px-6 rounded-full shadow-md transition-colors flex items-center gap-2">
                        <i class="ph ph-plus"></i> Registrar mi primera mascota
                    </a>
                </div>
            @endforelse
            
        </div>
    </div>
</div>
@endsection