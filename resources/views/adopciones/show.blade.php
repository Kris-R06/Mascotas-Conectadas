@extends('layout.admin')

@section('content')

@php
    $isOwner = auth()->check() && auth()->id() === $adopcion->mascota->user_id;
    $isAdopter = auth()->check() && auth()->id() === $adopcion->user_id;
    $canView = $adopcion->estatus === 'pendiente' || $isOwner || $isAdopter;
@endphp

<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans">
    <div class="max-w-6xl mx-auto space-y-8">
        @if(!$canView)
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-12 text-center max-w-2xl mx-auto mt-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 text-slate-400 mb-6">
                    <i class="ph-fill ph-lock-key text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-blue-950 mb-3">Adopción no disponible</h2>
                <p class="text-slate-500 mb-8 text-lg">
                    Esta mascota ya ha sido adoptada o se encuentra actualmente en proceso de revisión con otra familia.
                </p>
                <a href="{{ route('adopciones.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-md transition-colors">
                    <i class="ph ph-arrow-left text-xl"></i> Ver otras mascotas
                </a>
            </div>

        @else
            @if($isOwner)
                <div id="deleteModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
                    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 transition-transform duration-300 relative border border-slate-100" id="deleteModalCard">
                        <button onclick="closeDeleteModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 bg-slate-100 p-2 rounded-full transition-colors">
                            <i class="ph ph-x text-xl"></i>
                        </button>

                        <div class="inline-flex p-4 bg-red-50 text-red-500 rounded-2xl mb-4 border border-red-100">
                            <i class="ph ph-warning-circle text-4xl animate-bounce"></i>
                        </div>

                        <h3 class="text-2xl font-bold text-slate-800 mb-2">¿Eliminar registro?</h3>
                        <p class="text-sm text-slate-500 mb-6">
                            ¿Estás seguro de que deseas eliminar esta adopción de <strong class="text-slate-800 capitalize">{{ $adopcion->mascota->nombre }}</strong>? Esta acción no se puede deshacer.
                        </p>

                        <form action="{{ route('adopciones.destroy', $adopcion) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="flex gap-3">
                                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-colors flex items-center justify-center gap-2 text-sm">
                                    <i class="ph ph-trash text-lg"></i> Sí, eliminar
                                </button>
                                <button type="button" onclick="closeDeleteModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl transition-colors text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-6">
                <div>
                    <h1 class="text-4xl font-extrabold text-blue-950 flex items-center gap-3">
                        <i class="ph ph-paw-print text-blue-500"></i>
                        Detalle de la adopción
                    </h1>
                    <p class="mt-2 text-slate-500 font-light">
                        Información completa de la mascota y contacto con quien la ofrece.
                    </p>
                </div>
                <a href="{{ route('adopciones.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-full transition-colors">
                    Volver a adopciones
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                    <div class="h-72 bg-slate-100 overflow-hidden">
                        @if($adopcion->mascota->foto)
                            <img src="{{ $adopcion->mascota->foto_url }}" alt="{{ $adopcion->mascota->nombre }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <i class="ph ph-image text-6xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-3xl font-bold text-blue-950 capitalize">{{ $adopcion->mascota->nombre }}</h2>
                                <p class="text-slate-500 mt-1">{{ $adopcion->mascota->descripcion ?? 'Mascota disponible para adopción.' }}</p>
                            </div>
                            
                            {{-- Estatus visible solo para Dueño o Adoptante --}}
                            @if($isOwner || $isAdopter)
                                @php
                                    $estatusTexto = match ($adopcion->estatus) {
                                        'pendiente' => 'Pendiente de adopción',
                                        'en_revision' => 'En revisión (Con interesado)',
                                        'aprobada' => 'Adopción aprobada',
                                        'rechazada' => 'Rechazada',
                                        'cancelada' => 'Cancelada',
                                        default => ucfirst($adopcion->estatus),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3.5 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm uppercase tracking-wide">
                                    {{ $estatusTexto }}
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 text-sm text-slate-700">
                            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4">
                                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Especie</span>
                                <span class="font-semibold">{{ $adopcion->mascota->especie->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4">
                                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Raza</span>
                                <span class="font-semibold">{{ $adopcion->mascota->raza ?? 'Mestizo' }}</span>
                            </div>
                            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4">
                                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Color</span>
                                <span class="font-semibold">{{ $adopcion->mascota->color ?? 'No registrado' }}</span>
                            </div>
                            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4">
                                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Edad</span>
                                <span class="font-semibold">{{ $adopcion->mascota->edad ?? 'No registrado' }}</span>
                            </div>
                            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4">
                                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Tamaño</span>
                                <span class="font-semibold">{{ $adopcion->mascota->tamaño ?? 'No registrado' }}</span>
                            </div>
                            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4">
                                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Amigable con niños</span>
                                <span class="font-semibold">{{ $adopcion->mascota->kid_friendly ? 'Sí' : 'No' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50/40 border border-blue-100 rounded-3xl p-6 shadow-sm h-fit">
                    <h3 class="text-xl font-bold text-blue-950 mb-4">Información de contacto</h3>
                    <p class="text-sm text-slate-600 mb-5">Datos del usuario encargado de esta mascota en adopción.</p>

                    @if($adopcion->mascota->user)
                        <div class="space-y-4">
                            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Nombre</p>
                                <p class="font-semibold text-slate-800">{{ $adopcion->mascota->user->name }}</p>
                            </div>
                            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Teléfono</p>
                                <p class="font-semibold text-slate-800">{{ $adopcion->mascota->user->telefono ?? 'No registrado' }}</p>
                            </div>
                            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Correo electrónico</p>
                                <p class="font-semibold text-slate-800">{{ $adopcion->mascota->user->email }}</p>
                            </div>

                            @if($isOwner)
                                {{-- 1. BOTONES PARA EL DUEÑO (EDITAR Y ELIMINAR) --}}
                                <div class="flex flex-wrap gap-3 pt-4 mt-4 border-t border-blue-100">
                                    <a href="{{ route('adopciones.edit', $adopcion) }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-full shadow-sm transition-colors w-full sm:w-auto flex-1">
                                        <i class="ph ph-pencil-simple"></i> Editar
                                    </a>

                                    <button type="button" onclick="openDeleteModal()" class="inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-full shadow-sm transition-colors w-full sm:w-auto flex-1">
                                        <i class="ph ph-trash"></i> Eliminar
                                    </button>
                                </div>

                                @php 
                                    $interesado = \App\Models\User::find($adopcion->user_id); 
                                @endphp
                                
                                @if($interesado && $adopcion->user_id !== $adopcion->mascota->user_id)
                                    <div class="mt-6 pt-6 border-t border-blue-200">
                                        <h4 class="text-lg font-bold text-blue-950 mb-1 flex items-center gap-2">
                                            <i class="ph-fill ph-hand-heart text-amber-500"></i> Solicitante interesado
                                        </h4>
                                        <p class="text-xs text-slate-600 mb-4">Esta persona desea adoptar a tu mascota. ¡Contáctala!</p>
                                        
                                        <div class="space-y-3">
                                            <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200 shadow-sm">
                                                <p class="text-[10px] uppercase tracking-wider text-amber-700 font-bold">Nombre</p>
                                                <p class="font-semibold text-slate-800">{{ $interesado->name }}</p>
                                            </div>
                                            <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200 shadow-sm">
                                                <p class="text-[10px] uppercase tracking-wider text-amber-700 font-bold">Teléfono</p>
                                                <p class="font-semibold text-slate-800">{{ $interesado->telefono ?? 'No registrado' }}</p>
                                            </div>
                                            <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200 shadow-sm">
                                                <p class="text-[10px] uppercase tracking-wider text-amber-700 font-bold">Correo electrónico</p>
                                                <p class="font-semibold text-slate-800">{{ $interesado->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @elseif($isAdopter)
                                <div class="pt-4 mt-4 border-t border-blue-100 text-center">
                                    <div class="inline-flex items-center gap-2 text-amber-600 font-bold bg-amber-50 px-4 py-3 rounded-xl border border-amber-200 w-full justify-center">
                                        <i class="ph-fill ph-clock text-xl"></i> Tu solicitud está en revisión. ¡Contacta al dueño!
                                    </div>
                                </div>

                            @elseif(auth()->check() && $adopcion->estatus === 'pendiente')
                                <div class="pt-4 mt-4 border-t border-blue-100">
                                    <form action="{{ route('adopciones.adoptar', $adopcion) }}" method="POST" onsubmit="return confirm('¿Estás seguro de enviar tu solicitud de adopción? El dueño será notificado.')">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition-colors text-base">
                                            <i class="ph-fill ph-heart"></i> ¡Quiero Adoptar!
                                        </button>
                                    </form>
                                </div>

                            @elseif(!auth()->check() && $adopcion->estatus === 'pendiente')
                                <div class="pt-4 mt-4 border-t border-blue-100">
                                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition-colors text-sm">
                                        <i class="ph ph-sign-in"></i> Inicia sesión para adoptar
                                    </a>
                                </div>
                            @endif

                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-blue-200 bg-white p-6 text-center text-blue-700">
                            No hay información de contacto disponible.
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalCard = document.getElementById('deleteModalCard');

    function openDeleteModal() {
        if(deleteModal && deleteModalCard) {
            deleteModal.classList.remove('opacity-0', 'pointer-events-none');
            deleteModalCard.classList.remove('scale-95');
            deleteModalCard.classList.add('scale-100');
        }
    }

    function closeDeleteModal() {
        if(deleteModal && deleteModalCard) {
            deleteModal.classList.add('opacity-0', 'pointer-events-none');
            deleteModalCard.classList.remove('scale-100');
            deleteModalCard.classList.add('scale-95');
        }
    }

    deleteModal?.addEventListener('click', (e) => {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });
</script>
@endsection