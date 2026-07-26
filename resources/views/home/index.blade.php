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

        <!-- Área de Contenido Principal -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            
            <!-- Hero Banner -->
            <section class="bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl p-8 text-white shadow-lg mb-8 flex items-center justify-between relative overflow-hidden">
                <div class="max-w-xl relative z-10">
                    <h2 class="text-3xl font-bold mb-3">Red comunitaria de cuidado animal</h2>
                    <p class="text-blue-50 text-lg mb-6">Ayuda a conectar mascotas perdidas con sus familias o dale un nuevo hogar a un amigo de cuatro patas.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="bg-white text-blue-700 px-6 py-2.5 rounded-full font-semibold shadow hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
                            <i class="ph-bold ph-plus"></i> Crear Reporte
                        </button>
                        <button class="bg-blue-700/50 border border-blue-200 px-6 py-2.5 rounded-full font-semibold hover:bg-blue-700 transition-colors text-center">
                            Explorar Adopciones
                        </button>
                    </div>
                </div>
                <!-- Decoración visual del Hero -->
                <div class="hidden lg:block absolute right-8 -bottom-4 opacity-20">
                    <i class="ph-fill ph-cat text-9xl"></i>
                    <i class="ph-fill ph-dog text-9xl ml-4"></i>
                </div>
            </section>

            <!-- Tarjetas de Resumen (Widgets) -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Widget: Adopciones -->
                <div class="bg-white p-6 rounded-xl border border-blue-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="bg-blue-100 p-3 rounded-lg text-blue-600">
                        <i class="ph-fill ph-heart text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-500 text-sm font-medium">Adopciones Activas</h3>
                        <p class="text-2xl font-bold text-blue-900 mt-1">42</p>
                    </div>
                </div>
                
                <!-- Widget: Extravíos -->
                <div class="bg-white p-6 rounded-xl border border-blue-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="bg-blue-100 p-3 rounded-lg text-blue-600">
                        <i class="ph-fill ph-magnifying-glass text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-500 text-sm font-medium">Extravíos Reportados</h3>
                        <p class="text-2xl font-bold text-blue-900 mt-1">15</p>
                    </div>
                </div>
                
                <!-- Widget: Avistamientos -->
                <div class="bg-white p-6 rounded-xl border border-blue-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="bg-blue-100 p-3 rounded-lg text-blue-600">
                        <i class="ph-fill ph-binoculars text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-500 text-sm font-medium">Avistamientos Recientes</h3>
                        <p class="text-2xl font-bold text-blue-900 mt-1">8</p>
                    </div>
                </div>
            </section>
            
        </main>
    </div>
</body>
</html>