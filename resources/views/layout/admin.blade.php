<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel — Mascotas Conectadas</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

{{-- Usamos Alpine.js (x-data) en el body para controlar el estado del menú --}}
<body class="bg-blue-50/30 text-slate-800 font-sans antialiased min-h-screen overflow-x-hidden" x-data="{ sidebarOpen: false }">

    <div class="md:hidden flex items-center justify-between bg-white border-b border-blue-100 px-6 py-4 sticky top-0 z-40">
        <div class="flex items-center gap-2 font-bold text-blue-950 text-lg">
            <i class="ph-fill ph-paw-print text-blue-500 text-2xl"></i>
            Mascotas Conectadas
        </div>
        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-blue-600 focus:outline-none">
            <i class="ph ph-list text-3xl"></i>
        </button>
    </div>

    <div class="flex min-h-screen relative">
        
        <div x-show="sidebarOpen" 
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden" 
             x-cloak>
        </div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-blue-100 flex-shrink-0 flex flex-col justify-between overflow-y-auto transition-transform duration-300 ease-in-out md:translate-x-0">
            
            <nav class="p-4 space-y-2">
                <div class="md:hidden flex justify-end mb-4">
                    <button @click="sidebarOpen = false" class="text-slate-400 hover:text-red-500 p-2 bg-slate-50 rounded-full">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>

                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('home') ? 'text-blue-900 bg-blue-50 border border-blue-100 shadow-sm' : 'text-slate-600 hover:text-blue-700 hover:bg-blue-50/70' }} rounded-lg font-semibold transition-colors">
                    <i class="ph-fill ph-house text-xl {{ request()->routeIs('home') ? 'text-blue-600' : '' }}"></i>
                    Inicio
                </a>
                <a href="{{ route('adopciones.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('adopciones.*') ? 'text-blue-900 bg-blue-50 border border-blue-100 shadow-sm' : 'text-slate-600 hover:text-blue-700 hover:bg-blue-50/70' }} rounded-lg font-medium transition-colors">
                    <i class="ph ph-heart text-xl"></i>
                    Adopciones
                </a>
                <a href="{{ route('extraviados.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('extraviados.*') ? 'text-blue-900 bg-blue-50 border border-blue-100 shadow-sm' : 'text-slate-600 hover:text-blue-700 hover:bg-blue-50/70' }} rounded-lg font-medium transition-colors">
                    <i class="ph ph-magnifying-glass text-xl"></i>
                    Extravíos
                </a>
                <a href="{{ route('avistamientos.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('avistamientos.*') ? 'text-blue-900 bg-blue-50 border border-blue-100 shadow-sm' : 'text-slate-600 hover:text-blue-700 hover:bg-blue-50/70' }} rounded-lg font-medium transition-colors">
                    <i class="ph ph-binoculars text-xl"></i>
                    Avistamientos
                </a>
                <a href="{{ route('mascotas.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('mascotas.*') ? 'text-blue-900 bg-blue-50 border border-blue-100 shadow-sm' : 'text-slate-600 hover:text-blue-700 hover:bg-blue-50/70' }} rounded-lg font-medium transition-colors">
                    <i class="ph ph-dog text-xl"></i>
                    Mis Mascotas
                </a>
                <a href="{{ route('perfil.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('perfil.*') ? 'text-blue-900 bg-blue-50 border border-blue-100 shadow-sm' : 'text-slate-500 hover:text-blue-700 hover:bg-blue-50/70' }} rounded-lg font-medium transition-colors">
                    <i class="ph ph-user-circle text-xl"></i>
                    Mi Perfil
                </a>
            </nav>

            <div class="mt-auto border-t border-slate-100 pt-4 pb-4 px-4 w-full bg-slate-50/50">
                
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors font-bold text-sm">
                            <i class="ph ph-sign-out text-xl"></i>
                            Cerrar Sesión
                        </button>
                    </form>
                @endauth

                @guest
                    <div class="mb-4 px-2 text-xs text-slate-500 text-center">
                        Únete para registrar a tus mascotas y encontrar su match ideal.
                    </div>
                    
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-colors font-bold text-sm shadow-md hover:shadow-lg">
                        <i class="ph ph-sign-in text-xl"></i>
                        Iniciar Sesión
                    </a>
                @endguest

            </div>
        </aside>

        <main class="flex-grow flex flex-col h-[calc(100vh-72px)] md:h-screen overflow-y-auto">
            @yield('content')
            @include('partials.footer')
        </main>
               
    </div>
</body>
</html>  