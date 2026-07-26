@extends('layout.admin')
@section('content')
<div class="w-full max-w-5xl mx-auto bg-slate-50 px-4 py-6 sm:px-6 md:p-10 font-sans">
    
    <div class="mb-10 border-b border-slate-200 pb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 flex items-center gap-3">
                <i class="ph ph-pencil-simple text-indigo-500"></i> Editar Mascota
            </h1>
            <p class="mt-2 text-slate-500 font-light">
                Actualiza el perfil de <strong class="text-indigo-600 capitalize">{{ $mascota->nombre }}</strong>, su estatus o sus parámetros de Smart Match.
            </p>
        </div>
        <a href="{{ route('mascotas.index') }}" class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2 font-semibold">
            <i class="ph ph-arrow-left"></i> Cancelar
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-8 bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl animate-pulse">
            <strong class="font-bold flex items-center gap-2"><i class="ph ph-warning-circle text-xl"></i> Revisa los siguientes errores:</strong>
            <ul class="list-disc list-inside mt-2 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mascotas.update', $mascota->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5 sm:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="flex flex-col gap-5">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2 mb-2">
                    <i class="ph ph-identification-card text-indigo-400"></i> Datos Básicos
                </h2>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $mascota->nombre) }}" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Especie</label>
                        <select name="especie_id" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow" required>
                            <option value="" disabled>Selecciona...</option>
                            @foreach($especies as $especie)
                                <option value="{{ $especie->id }}" {{ old('especie_id', $mascota->especie_id) == $especie->id ? 'selected' : '' }}>
                                    {{ $especie->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Raza</label>
                        <input type="text" name="raza" value="{{ old('raza', $mascota->raza) }}" placeholder="Ej. Mestizo" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Color</label>
                        <input type="text" name="color" value="{{ old('color', $mascota->color) }}" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Tamaño</label>
                        <select name="tamaño" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                            <option value="Pequeño" {{ old('tamaño', $mascota->tamaño) == 'Pequeño' ? 'selected' : '' }}>Pequeño</option>
                            <option value="Mediano" {{ old('tamaño', $mascota->tamaño) == 'Mediano' ? 'selected' : '' }}>Mediano</option>
                            <option value="Grande" {{ old('tamaño', $mascota->tamaño) == 'Grande' ? 'selected' : '' }}>Grande</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Edad</label>
                        <input type="text" name="edad" value="{{ old('edad', $mascota->edad) }}" placeholder="Ej. 2 meses" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Estatus Actual</label>
                    <select name="estatus" class="w-full bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow" required>
                        <option value="safe" {{ old('estatus', $mascota->estatus) == 'safe' ? 'selected' : '' }}>A salvo en casa</option>
                        <option value="adopcion" {{ old('estatus', $mascota->estatus) == 'adopcion' ? 'selected' : '' }}>En adopción</option>
                        <option value="extraviado" {{ old('estatus', $mascota->estatus) == 'extraviado' ? 'selected' : '' }}>Extraviado (Se busca)</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-5">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2 mb-2">
                    <i class="ph ph-heart text-indigo-400"></i> Perfil y Smart Match
                </h2>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Descripción / Personalidad</label>
                    <textarea name="descripcion" rows="3" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow" placeholder="Cuéntanos un poco sobre su comportamiento, si está esterilizado, etc.">{{ old('descripcion', $mascota->descripcion) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Foto de la Mascota</label>
                    @if($mascota->foto)
                        <div class="flex items-center gap-4 mb-3">
                            <img src="{{ asset('storage/' . $mascota->foto) }}" alt="Foto actual" class="w-16 h-16 object-cover rounded-lg border border-slate-200 shadow-sm">
                            <span class="text-xs text-slate-500 italic">Si subes una nueva foto, reemplazará a la actual.</span>
                        </div>
                    @endif
                    <input type="file" name="foto" accept="image/*" class="w-full bg-slate-50 border border-slate-200 p-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>

                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 mt-2">
                    <label class="block text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                        <i class="ph ph-lightning text-amber-500 text-lg"></i> Nivel de Energía (1-10)
                    </label>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
                        <span class="text-xs text-slate-500">Sedentario (1)</span>
                        <input type="range" name="energy_level" min="1" max="10" value="{{ old('energy_level', $mascota->energy_level) }}" class="w-full accent-indigo-500">
                        <span class="text-xs text-slate-500">Atlético (10)</span>
                    </div>

                    <div class="mt-6 flex flex-col gap-3">
                        <label class="flex items-center gap-3 cursor-pointer hover:text-indigo-600 transition-colors text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="space_needed" value="1" {{ old('space_needed', $mascota->space_needed) ? 'checked' : '' }} class="accent-indigo-500 w-5 h-5 rounded">
                            Requiere espacio amplio / Patio
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:text-indigo-600 transition-colors text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="kid_friendly" value="1" {{ old('kid_friendly', $mascota->kid_friendly) ? 'checked' : '' }} class="accent-indigo-500 w-5 h-5 rounded">
                            Es apto/amigable con niños
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 border-t border-slate-100 pt-6 flex justify-stretch sm:justify-end">
            <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                <i class="ph ph-arrows-clockwise text-xl"></i> Actualizar Mascota
            </button>
        </div>
    </form>
</div>
@endsection