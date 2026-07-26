@extends('layout.admin')

@section('content')
<div class="w-full flex-grow bg-white p-6 md:p-10 font-sans">
    <div class="max-w-6xl mx-auto space-y-8">
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
                        <img src="{{ asset('storage/' . $adopcion->mascota->foto) }}" alt="{{ $adopcion->mascota->nombre }}" class="w-full h-full object-cover">
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
                        <span class="inline-flex items-center px-3.5 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm uppercase tracking-wide">
                            {{ $adopcion->estatus }}
                        </span>
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
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-blue-200 bg-white p-6 text-center text-blue-700">
                        No hay información de contacto disponible.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
