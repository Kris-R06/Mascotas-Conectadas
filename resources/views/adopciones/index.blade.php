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
        
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="openSmartMatchModal()" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2 whitespace-nowrap">
                <i class="ph ph-magic-wand text-xl"></i> Smart Match
            </button>

            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2 whitespace-nowrap">
                <i class="ph ph-plus-circle text-xl"></i> Agregar adopción
            </a>
        </div>
    </div>

    <div class="w-full">
        @php $hayAdopciones = false; @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
            
            @foreach($adopciones as $adopcion)
                @if($adopcion->mascota && $adopcion->estatus === 'pendiente')
                    @php $hayAdopciones = true; @endphp
                    
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

<div id="smartMatchModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 md:p-8 transform scale-95 transition-transform duration-300 relative border border-slate-100 flex flex-col max-h-[90vh]" id="smartMatchCard">
        
        <button onclick="closeSmartMatchModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 bg-slate-100 p-2 rounded-full transition-colors z-10">
            <i class="ph ph-x text-xl"></i>
        </button>

        <div class="text-center mb-6">
            <div class="inline-flex p-4 bg-amber-50 text-amber-500 rounded-2xl mb-4 shadow-sm border border-amber-100">
                <i class="ph ph-magic-wand text-4xl animate-bounce"></i>
            </div>
            <h3 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-2">Smart Match</h3>
            <p class="text-sm text-slate-500">
                Responde unas breves preguntas y nuestro algoritmo encontrará al compañero peludo que mejor se adapte a tu estilo de vida.
            </p>
        </div>

        <form action="{{ route('adopciones.smart-match') }}" method="GET" id="smartMatchForm" class="flex-grow overflow-y-auto bg-slate-50 p-4 sm:p-6 rounded-2xl border border-slate-100 mb-6 custom-scrollbar">
            <div class="space-y-8">
                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <i class="ph ph-sneaker text-indigo-500 text-xl"></i> 1. ¿Cómo describirías tu nivel de actividad física?
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-all has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500 group">
                            <input type="radio" name="q1" value="1" class="hidden" required>
                            <div class="flex-grow">
                                <span class="block text-sm font-bold text-slate-700 group-has-[:checked]:text-indigo-700">Relajado</span>
                                <span class="block text-xs text-slate-500">Prefiero ver series y descansar.</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-all has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500 group">
                            <input type="radio" name="q1" value="2" class="hidden">
                            <div class="flex-grow">
                                <span class="block text-sm font-bold text-slate-700 group-has-[:checked]:text-indigo-700">Moderado</span>
                                <span class="block text-xs text-slate-500">Caminatas ocasionales de fin de semana.</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-all has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500 group">
                            <input type="radio" name="q1" value="3" class="hidden">
                            <div class="flex-grow">
                                <span class="block text-sm font-bold text-slate-700 group-has-[:checked]:text-indigo-700">Activo</span>
                                <span class="block text-xs text-slate-500">Hago ejercicio o salgo a caminar a diario.</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-all has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500 group">
                            <input type="radio" name="q1" value="4" class="hidden">
                            <div class="flex-grow">
                                <span class="block text-sm font-bold text-slate-700 group-has-[:checked]:text-indigo-700">Muy Activo</span>
                                <span class="block text-xs text-slate-500">Corro, hago senderismo, no paro.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <i class="ph ph-clock text-indigo-500 text-xl"></i> 2. ¿Cuánto tiempo diario le dedicarás a jugar o pasear?
                    </h4>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 group">
                            <input type="radio" name="q2" value="1" class="w-4 h-4 accent-indigo-600 mr-3" required>
                            <span class="text-sm font-semibold text-slate-700 group-has-[:checked]:text-indigo-700">Menos de 30 minutos (Paseos rápidos para ir al baño)</span>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 group">
                            <input type="radio" name="q2" value="2" class="w-4 h-4 accent-indigo-600 mr-3">
                            <span class="text-sm font-semibold text-slate-700 group-has-[:checked]:text-indigo-700">Entre 30 minutos y 1 hora</span>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 group">
                            <input type="radio" name="q2" value="3" class="w-4 h-4 accent-indigo-600 mr-3">
                            <span class="text-sm font-semibold text-slate-700 group-has-[:checked]:text-indigo-700">Más de 1 hora (Juegos largos y parque)</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <i class="ph ph-sparkle text-indigo-500 text-xl"></i> 3. ¿Cómo imaginas a tu compañero ideal?
                    </h4>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 group">
                            <input type="radio" name="q3" value="1" class="w-4 h-4 accent-indigo-600 mr-3" required>
                            <span class="text-sm font-semibold text-slate-700 group-has-[:checked]:text-indigo-700">Tranquilo, dormilón e independiente.</span>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 group">
                            <input type="radio" name="q3" value="2" class="w-4 h-4 accent-indigo-600 mr-3">
                            <span class="text-sm font-semibold text-slate-700 group-has-[:checked]:text-indigo-700">Juguetón pero que sepa relajarse en casa.</span>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-indigo-50 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 group">
                            <input type="radio" name="q3" value="3" class="w-4 h-4 accent-indigo-600 mr-3">
                            <span class="text-sm font-semibold text-slate-700 group-has-[:checked]:text-indigo-700">Un torbellino de energía, siempre listo para la acción.</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
                            <i class="ph ph-house-line text-amber-500 text-lg"></i> ¿Tienes patio/jardín?
                        </h4>
                        <div class="flex gap-3">
                            <label class="flex-1 text-center p-2 border border-slate-200 rounded-xl cursor-pointer hover:bg-amber-50 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-700 font-semibold text-sm transition-all text-slate-600">
                                <input type="radio" name="has_yard" value="1" class="hidden" required> Sí
                            </label>
                            <label class="flex-1 text-center p-2 border border-slate-200 rounded-xl cursor-pointer hover:bg-amber-50 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-700 font-semibold text-sm transition-all text-slate-600">
                                <input type="radio" name="has_yard" value="0" class="hidden"> No
                            </label>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
                            <i class="ph ph-baby text-amber-500 text-lg"></i> ¿Hay niños en casa?
                        </h4>
                        <div class="flex gap-3">
                            <label class="flex-1 text-center p-2 border border-slate-200 rounded-xl cursor-pointer hover:bg-amber-50 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-700 font-semibold text-sm transition-all text-slate-600">
                                <input type="radio" name="has_kids" value="1" class="hidden" required> Sí
                            </label>
                            <label class="flex-1 text-center p-2 border border-slate-200 rounded-xl cursor-pointer hover:bg-amber-50 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-700 font-semibold text-sm transition-all text-slate-600">
                                <input type="radio" name="has_kids" value="0" class="hidden"> No
                            </label>
                        </div>
                    </div>

                </div>

            </div>
        </form>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button onclick="closeSmartMatchModal()" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-8 rounded-xl transition-colors text-sm">
                Cancelar
            </button>
        </div>

    </div>
</div>

<script>
    const smModal = document.getElementById('smartMatchModal');
    const smCard = document.getElementById('smartMatchCard');

    function openSmartMatchModal() {
        // Quita la invisibilidad y permite los clics
        smModal.classList.remove('opacity-0', 'pointer-events-none');
        // Hace el efecto de zoom in suave
        smCard.classList.remove('scale-95');
        smCard.classList.add('scale-100');
    }

    function closeSmartMatchModal() {
        // Vuelve a ocultar y bloquear clics
        smModal.classList.add('opacity-0', 'pointer-events-none');
        // Hace el efecto de zoom out suave
        smCard.classList.remove('scale-100');
        smCard.classList.add('scale-95');
    }

    // Permitir cerrar el modal haciendo clic fuera de la tarjeta blanca
    smModal.addEventListener('click', (e) => {
        if (e.target === smModal) {
            closeSmartMatchModal();
        }
    });
</script>

@endsection 