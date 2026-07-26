@extends('layout.admin')

@section('content')
<main class="flex-1 overflow-y-auto p-6 md:p-10">
    <div class="max-w-3xl mx-auto">

        <!-- Encabezado -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold text-blue-900 flex items-center gap-3">
                    <i class="ph-fill ph-user-circle text-3xl text-blue-600"></i>
                    Mi Perfil
                </h1>
                <p class="text-slate-500 mt-1">Consulta tu información personal.</p>
            </div>
            <a href="{{ route('perfil.edit') }}"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                <i class="ph ph-pencil-simple text-lg"></i>
                Editar perfil
            </a>
        </div>

        <!-- Alerta de éxito -->
        @if (session('status'))
            <div class="mb-6 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <i class="ph-fill ph-check-circle text-lg"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Información Personal -->
        <div class="bg-white border border-blue-100 rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                <i class="ph ph-identification-card text-xl text-blue-600"></i>
                Información Personal
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-slate-500 mb-1">Nombre completo</p>
                    <p class="font-medium text-slate-800">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-slate-500 mb-1">Correo electrónico</p>
                    <p class="font-medium text-slate-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-slate-500 mb-1">Teléfono</p>
                    <p class="font-medium text-slate-800">{{ $user->telefono }}</p>
                </div>
                <div>
                    <p class="text-slate-500 mb-1">Tipo de usuario</p>
                    <p class="font-medium text-slate-800">{{ $user->tipoUser->nombre ?? 'No definido' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-slate-500 mb-1">Dirección</p>
                    <p class="font-medium text-slate-800">{{ $user->direccion }}</p>
                </div>
            </div>
        </div>

        <!-- Preferencias del hogar -->
        <div class="bg-white border border-blue-100 rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                <i class="ph ph-house-line text-xl text-blue-600"></i>
                Preferencias del hogar
            </h2>

            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-lg flex-1">
                    <i class="ph-fill {{ $user->has_yard ? 'ph-check-circle text-green-600' : 'ph-x-circle text-slate-300' }} text-xl"></i>
                    <span class="text-sm font-medium text-slate-700">Cuenta con patio / jardín</span>
                </div>
                <div class="flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-lg flex-1">
                    <i class="ph-fill {{ $user->kids ? 'ph-check-circle text-green-600' : 'ph-x-circle text-slate-300' }} text-xl"></i>
                    <span class="text-sm font-medium text-slate-700">Hay niños en casa</span>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection