@extends('layout.admin')

@section('content')
<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-100 pb-6">
            <div>
                <h1 class="text-4xl font-extrabold text-blue-950 flex items-center gap-3">
                    <i class="ph ph-paw-print text-blue-500"></i>
                    Editar adopción
                </h1>
                <p class="mt-2 text-slate-500 font-light">
                    Actualiza la información de la adopción y el estado del proceso.
                </p>
            </div>
            <a href="{{ route('adopciones.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-full transition-colors">
                Volver a adopciones
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-blue-50/40 border border-blue-100 rounded-3xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-blue-950 mb-5">Datos de la adopción</h2>

                <form action="{{ route('adopciones.update', $adopcion) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Mascota asociada</label>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                    @if($adopcion->mascota && $adopcion->mascota->foto)
                                        <img src="{{ asset('storage/' . $adopcion->mascota->foto) }}" alt="{{ $adopcion->mascota->nombre }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="ph ph-image text-2xl text-slate-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Información visible</p>
                                    <h3 class="font-semibold text-slate-800 capitalize">{{ $adopcion->mascota->nombre ?? 'Sin mascota' }}</h3>
                                    <p class="text-sm text-slate-500">{{ $adopcion->mascota->especie->nombre ?? 'Sin especie' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-slate-700 mb-2">Adoptante</label>
                        <select name="user_id" id="user_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sin asignar aún</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('user_id', $adopcion->user_id) == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="estatus" class="block text-sm font-semibold text-slate-700 mb-2">Estado</label>
                        <select name="estatus" id="estatus" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="pendiente" {{ old('estatus', $adopcion->estatus) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_revision" {{ old('estatus', $adopcion->estatus) == 'en_revision' ? 'selected' : '' }}>En revisión</option>
                            <option value="aprobada" {{ old('estatus', $adopcion->estatus) == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                            <option value="rechazada" {{ old('estatus', $adopcion->estatus) == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                            <option value="cancelada" {{ old('estatus', $adopcion->estatus) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-2xl shadow-md transition-all">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-blue-950 mb-5">Información de la mascota</h2>

                @if($adopcion->mascota)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-32 h-32 rounded-2xl bg-slate-200 overflow-hidden flex items-center justify-center">
                                @if($adopcion->mascota->foto)
                                    <img src="{{ asset('storage/' . $adopcion->mascota->foto) }}" alt="{{ $adopcion->mascota->nombre }}" class="w-full h-full object-cover">
                                @else
                                    <i class="ph ph-image text-4xl text-slate-400"></i>
                                @endif
                            </div>

                            <div class="flex-1 space-y-2 text-sm text-slate-700">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-bold text-blue-950 capitalize">{{ $adopcion->mascota->nombre }}</h3>
                                    <span class="text-xs uppercase tracking-wider bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">{{ $adopcion->mascota->estatus }}</span>
                                </div>

                                <p class="text-slate-600">{{ $adopcion->mascota->descripcion }}</p>

                                <div class="grid grid-cols-2 gap-3 mt-3">
                                    <div>
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Especie</span>
                                        <span class="font-semibold">{{ $adopcion->mascota->especie->nombre ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Raza</span>
                                        <span class="font-semibold">{{ $adopcion->mascota->raza ?? 'Mestizo' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Color</span>
                                        <span class="font-semibold">{{ $adopcion->mascota->color ?? 'No registrado' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Tamaño</span>
                                        <span class="font-semibold">{{ $adopcion->mascota->tamaño ?? 'No registrado' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Edad</span>
                                        <span class="font-semibold">{{ $adopcion->mascota->edad ?? 'No registrado' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Amigable con niños</span>
                                        <span class="font-semibold">{{ $adopcion->mascota->kid_friendly ? 'Sí' : 'No' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-blue-200 bg-blue-50/50 p-6 text-center text-blue-700">
                        No hay información de mascota asociada.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
