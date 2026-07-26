@extends('layout.admin')
@section('content')
<div class="w-full min-h-screen bg-slate-50 py-10 px-4 sm:px-6 lg:px-8 font-sans overflow-y-auto">
    
    <div class="max-w-6xl mx-auto mb-20">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <a href="{{ route('mascotas.index') }}" class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2 font-semibold w-fit">
                <i class="ph ph-arrow-left text-xl"></i> Volver a mi manada
            </a>
            <div class="flex gap-3">
                <a href="{{ route('mascotas.edit', $mascota->id) }}" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors flex items-center gap-2 text-sm">
                    <i class="ph ph-pencil-simple text-lg"></i> Editar
                </a>
                <button onclick="openQrModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition-colors flex items-center gap-2 text-sm">
                    <i class="ph ph-qr-code text-lg"></i> Ver QR
                </button>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col md:flex-row">
            
            <div class="md:w-1/3 relative bg-slate-100 flex-shrink-0">
                @if($mascota->estatus === 'safe')
                    <div class="absolute top-6 left-6 z-10 bg-emerald-500 text-white text-xs uppercase tracking-wider font-bold px-4 py-2 rounded-full shadow-lg flex items-center gap-2">
                        <i class="ph ph-house text-lg"></i> A salvo en casa
                    </div>
                @elseif($mascota->estatus === 'adopcion')
                    <div class="absolute top-6 left-6 z-10 bg-indigo-500 text-white text-xs uppercase tracking-wider font-bold px-4 py-2 rounded-full shadow-lg flex items-center gap-2">
                        <i class="ph ph-heart text-lg"></i> En Adopción
                    </div>
                @elseif($mascota->estatus === 'extraviado')
                    <div class="absolute top-6 left-6 z-10 bg-red-500 text-white text-xs uppercase tracking-wider font-bold px-4 py-2 rounded-full shadow-lg flex items-center gap-2 animate-pulse">
                        <i class="ph ph-siren text-lg"></i> Extraviado
                    </div>
                @endif

                <div class="h-80 md:h-full w-full">
                    @if($mascota->foto)
                        <img src="{{ asset('storage/' . $mascota->foto) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100">
                            <i class="ph ph-camera-slash text-6xl mb-3 text-slate-300"></i>
                            <span class="text-sm font-medium">Sin foto de perfil</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="md:w-2/3 p-8 md:p-12 flex flex-col justify-center">
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-2 capitalize">
                    {{ $mascota->nombre }}
                </h1>
                
                <p class="text-slate-500 flex items-center gap-2 mb-8">
                    <i class="ph ph-paw-print text-indigo-500"></i> 
                    Registrado el {{ $mascota->created_at ? $mascota->created_at->format('d M, Y') : now()->format('d M, Y') }}
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mb-10">
                    <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-50">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Especie</span>
                        <span class="font-bold text-slate-800 capitalize">{{ $mascota->especie->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-50">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Raza</span>
                        <span class="font-bold text-slate-800 capitalize">{{ $mascota->raza ?? 'Mestizo' }}</span>
                    </div>
                    <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-50">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Edad</span>
                        <span class="font-bold text-slate-800 capitalize">{{ $mascota->edad ?? 'Desconocida' }}</span>
                    </div>
                    <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-50">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Tamaño</span>
                        <span class="font-bold text-slate-800 capitalize">{{ $mascota->tamaño ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="mb-10">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-3">
                        <i class="ph ph-text-align-left text-indigo-500"></i> Sobre {{ $mascota->nombre }}
                    </h3>
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 text-slate-600 text-sm leading-relaxed">
                        {{ $mascota->descripcion ?: 'No se agregó ninguna descripción adicional.' }}
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-4">
                        <i class="ph ph-lightning text-amber-500"></i> Perfil Smart Match
                    </h3>
                    
                    <div class="mb-5">
                        <div class="flex justify-between text-xs font-bold text-slate-500 mb-2">
                            <span>Nivel de Energía: {{ $mascota->energy_level }}/10</span>
                            <span>{{ $mascota->energy_level > 7 ? 'Muy Atlético' : ($mascota->energy_level < 4 ? 'Tranquilo' : 'Moderado') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-amber-400 to-amber-600 h-2.5 rounded-full" style="width: {{ ($mascota->energy_level / 10) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4">
                        @if($mascota->kid_friendly)
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-full">
                                <i class="ph ph-baby text-base"></i> Apto para niños
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-500 border border-slate-200 text-xs font-bold px-3 py-1.5 rounded-full">
                                <i class="ph ph-prohibit text-base"></i> No apto para niños
                            </span>
                        @endif

                        @if($mascota->space_needed)
                            <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-bold px-3 py-1.5 rounded-full">
                                <i class="ph ph-house-line text-base"></i> Requiere patio amplio
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-500 border border-slate-200 text-xs font-bold px-3 py-1.5 rounded-full">
                                <i class="ph ph-building-apartment text-base"></i> Se adapta a interiores
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="qrModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 transition-transform duration-300 relative border border-slate-100" id="qrModalCard">
        
        <button onclick="closeQrModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 bg-slate-100 p-2 rounded-full transition-colors">
            <i class="ph ph-x text-xl"></i>
        </button>

        <div class="inline-flex p-3 bg-indigo-50 text-indigo-600 rounded-2xl mb-3">
            <i class="ph ph-qr-code text-3xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-1">Placa QR Identificadora</h3>
        <p class="text-xs text-slate-500 mb-6">Escanea esta placa para ver la ficha pública de <span class="font-bold capitalize text-slate-700">{{ $mascota->nombre }}</span>.</p>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 inline-block mb-6 shadow-inner">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode(route('mascotas.show', $mascota->id)) }}" 
                 alt="Código QR de {{ $mascota->nombre }}" 
                 class="w-48 h-48 mx-auto rounded-lg shadow-sm">
        </div>

        <p class="text-[11px] text-slate-400 font-mono mb-6 truncate px-2">
            ID: {{ $mascota->qr ?? 'UUID-PENDIENTE' }}
        </p>

        <div class="flex gap-3">
            <button onclick="window.print()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-colors flex items-center justify-center gap-2 text-sm">
                <i class="ph ph-printer text-lg"></i> Imprimir
            </button>
            <button onclick="closeQrModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl transition-colors text-sm">
                Cerrar
            </button>
        </div>

    </div>
</div>

<script>
    const modal = document.getElementById('qrModal');
    const modalCard = document.getElementById('qrModalCard');

    function openQrModal() {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalCard.classList.remove('scale-95');
        modalCard.classList.add('scale-100');
    }

    function closeQrModal() {
        modal.classList.add('opacity-0', 'pointer-events-none');
        modalCard.classList.remove('scale-100');
        modalCard.classList.add('scale-95');
    }

    // Cerrar al hacer clic fuera de la tarjeta
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeQrModal();
        }
    });
</script>
@endsection