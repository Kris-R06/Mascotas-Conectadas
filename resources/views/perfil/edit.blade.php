@extends('layout.admin')

@section('content')
<main class="flex-1 overflow-y-auto p-6 md:p-10">
    <div class="max-w-3xl mx-auto">

        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-blue-900 flex items-center gap-3">
                <i class="ph-fill ph-pencil-simple text-3xl text-blue-600"></i>
                Editar Perfil
            </h1>
            <p class="text-slate-500 mt-1">Actualiza tu información personal.</p>
        </div>

        <!-- Errores de validación -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i class="ph-fill ph-warning-circle text-lg"></i>
                    Corrige los siguientes errores:
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('perfil.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PATCH')

            <!-- Información Personal -->
            <div class="bg-white border border-blue-100 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                    <i class="ph ph-identification-card text-xl text-blue-600"></i>
                    Información Personal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-600 mb-1">Nombre completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-600 mb-1">Correo electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label for="telefono" class="block text-sm font-medium text-slate-600 mb-1">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $user->telefono) }}"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
                    </div>


                    <div class="md:col-span-2">
                        <label for="direccion" class="block text-sm font-medium text-slate-600 mb-1">Dirección</label>
                        <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $user->direccion) }}"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Preferencias del hogar -->
            <div class="bg-white border border-blue-100 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-blue-900 mb-1 flex items-center gap-2">
                    <i class="ph ph-house-line text-xl text-blue-600"></i>
                    Preferencias del hogar
                </h2>
                <p class="text-sm text-slate-500 mb-4">
                    Esta información se usa para sugerirte mascotas compatibles en adopción.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <label class="flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-blue-50/50 transition flex-1">
                        <input type="checkbox" name="has_yard" value="1"
                            {{ old('has_yard', $user->has_yard) ? 'checked' : '' }}
                            class="w-5 h-5 rounded text-blue-600 focus:ring-blue-400">
                        <span class="text-sm font-medium text-slate-700 flex items-center gap-2">
                            <i class="ph ph-tree text-lg"></i>
                            Cuento con patio / jardín
                        </span>
                    </label>

                    <label class="flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-blue-50/50 transition flex-1">
                        <input type="checkbox" name="kids" value="1"
                            {{ old('kids', $user->kids) ? 'checked' : '' }}
                            class="w-5 h-5 rounded text-blue-600 focus:ring-blue-400">
                        <span class="text-sm font-medium text-slate-700 flex items-center gap-2">
                            <i class="ph ph-baby text-lg"></i>
                            Hay niños en casa
                        </span>
                    </label>
                </div>
            </div>

            <!-- Seguridad -->
            <div class="bg-white border border-blue-100 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-blue-900 mb-1 flex items-center gap-2">
                    <i class="ph ph-lock-key text-xl text-blue-600"></i>
                    Seguridad
                </h2>
                <p class="text-sm text-slate-500 mb-4">
                    Deja estos campos en blanco si no deseas cambiar tu contraseña.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-600 mb-1">Nueva contraseña</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-600 mb-1">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('perfil.index') }}"
                    class="px-5 py-2.5 rounded-lg font-medium text-slate-600 hover:bg-slate-100 transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                    <i class="ph-fill ph-floppy-disk text-lg"></i>
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>
</main>
@endsection