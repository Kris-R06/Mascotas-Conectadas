<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas Conectadas</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-blue-50/30 text-slate-800 font-sans antialiased h-screen flex flex-col overflow-hidden">

    <!-- Contenedor Principal (Layout) -->
    <div class="flex flex-1 overflow-hidden">
        
        <!-- Sidebar Izquierda -->
        <aside class="w-64 bg-white border-r border-blue-100 flex-shrink-0 flex flex-col justify-between overflow-y-auto hidden md:flex">
            <nav class="p-4 space-y-2">
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-blue-900 bg-blue-50 rounded-lg font-semibold transition-colors border border-blue-100 shadow-sm">
                    <i class="ph-fill ph-house text-xl text-blue-600"></i>
                    Inicio
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-blue-700 hover:bg-blue-50/70 rounded-lg font-medium transition-colors">
                    <i class="ph ph-heart text-xl"></i>
                    Adopciones
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-blue-700 hover:bg-blue-50/70 rounded-lg font-medium transition-colors">
                    <i class="ph ph-magnifying-glass text-xl"></i>
                    Extravíos
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:text-blue-700 hover:bg-blue-50/70 rounded-lg font-medium transition-colors">
                    <i class="ph ph-binoculars text-xl"></i>
                    Avistamientos
                </a>
            </nav>
            
            <!-- Menú secundario inferior en el sidebar -->
            <div class="p-4 border-t border-blue-50">
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-blue-700 hover:bg-blue-50/70 rounded-lg font-medium transition-colors">
                    <i class="ph ph-gear text-xl"></i>
                    Configuración
                </a>
            </div>
        </aside>

        @yield('content')

        @include('partials.footer')
               
    </div>
</body>
</html>