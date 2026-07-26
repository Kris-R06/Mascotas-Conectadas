<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas Conectadas — Adopción y reencuentro de mascotas</title>
    <meta name="description" content="Adopta con Smart Matching, reporta mascotas extraviadas y activa una búsqueda con mapa en tiempo real y ficha con código QR.">

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind (CDN para prototipo) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy:   '#0B2545',
                        sky:    '#1F6FEB',
                        pastel: '#EAF2FF',
                        coral:  '#FF6B4A',
                        slate:  '#64748B',
                        black:  '#000000',
                    },
                    fontFamily: {
                        display: ['Sora', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Sora', sans-serif; }

        .card-hover { transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -18px rgba(11,37,69,0.18); border-color: #1F6FEB; }
    </style>
</head>
<body class="bg-white text-navy antialiased">

    {{-- ================= NAVBAR ================= --}}
    <header x-data="{ open: false }" class="sticky top-0 z-50 bg-sky/20 backdrop-blur-md border-b border-slate/10">
        <nav class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-display font-extrabold text-xl text-navy">
                <img src="{{ asset('images/image.svg') }}" alt="Mascotas Conectadas" class="h-12 w-12 shrink-0 object-contain">
                Mascotas Conectadas
            </a>

            <div class="hidden lg:flex items-center gap-6 ml-auto">
                <div class="flex items-center gap-5 text-sm font-medium text-navy/80">
                    <a href="#servicios" class="hover:text-sky transition-colors">Reportar extravío</a>
                    <a href="#como-funciona" class="hover:text-sky transition-colors">Cómo funciona</a>
                    <a href="{{ route('contacto') }}" class="hover:text-sky transition-colors">Contactanos</a>
                </div>
                @auth
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-navy text-white text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-navy/90 transition-colors shadow-sm shadow-navy/30">
                        Ir al panel
                    </a>
                @endauth
                @guest
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-coral text-white text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-coral/90 transition-colors shadow-sm shadow-coral/30">
                    Iniciar sesión
                </a>
                @endguest
            </div>

            <button @click="open = !open" class="lg:hidden text-navy text-2xl" aria-label="Abrir menú">
                <i x-show="!open" class="ph-bold ph-list"></i>
                <i x-show="open" x-cloak class="ph-bold ph-x"></i>
            </button>
        </nav>

        <div x-show="open" x-cloak x-transition class="lg:hidden border-t border-slate/10 bg-white px-6 py-5 space-y-4">
            <a href="#servicios" class="block text-black font-medium">Reportar extravío</a>
            <a href="#como-funciona" class="block text-black font-medium">Cómo funciona</a>
            <hr class="border-slate/10">
            <a href="{{ route('login') }}" class="block text-navy/70 font-semibold">Iniciar sesión</a>
        </div>
    </header>

    {{-- ================= HERO ================= --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8 pt-16 pb-20 lg:pt-24 lg:pb-28 grid lg:grid-cols-2 gap-8 lg:gap-10 items-center">
        <div>

            <h1 class="mt-6 font-display font-extrabold text-4xl sm:text-5xl leading-[1.08] text-navy">
                La red que reúne mascotas <span class="text-sky">con sus familias</span>
            </h1>

            <p class="mt-6 text-slate text-lg leading-relaxed max-w-lg">
                Adopta con Smart Matching, reporta un extravío en segundos y activa un mapa de búsqueda con ficha imprimible en PDF y código QR.
            </p>

            <div class="mt-9 flex flex-wrap gap-4">
                <a href="{{ route('adopciones.index') }}" class="inline-flex items-center gap-2 bg-navy text-white font-semibold px-6 py-3.5 rounded-full hover:bg-navy/90 transition-colors">
                    <i class="ph-fill ph-heart"></i> Adoptar ahora
                </a>
                <a href="{{ route('extraviados.index') }}" class="inline-flex items-center gap-2 border border-navy/15 text-navy font-semibold px-6 py-3.5 rounded-full hover:border-sky hover:text-sky transition-colors">
                    <i class="ph-bold ph-map-pin"></i> Reportar una mascota
                </a>
            </div>
        </div>

        <div class="max-w-[500px] w-full mx-auto">
            <img
                src="{{ asset('images/mascotasconectadas.jpg') }}"
                alt="Mascotas Conectadas"
                class="w-full aspect-square object-cover rounded-3xl shadow-2xl shadow-sky/20"
            >
        </div>
    </section>

    {{-- ================= SERVICIOS ================= --}}
    <section id="servicios" class="bg-pastel/40 py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="max-w-xl">
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-navy">Todo lo que necesitas para adoptar o reencontrar</h2>
                <p class="mt-4 text-slate leading-relaxed">Herramientas conectadas entre sí, pensadas para actuar rápido en el momento que más importa.</p>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="card-hover bg-white border border-slate/10 rounded-2xl p-7">
                    <div class="w-12 h-12 rounded-xl bg-sky/10 flex items-center justify-center">
                        <i class="ph-duotone ph-sparkle text-sky text-2xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-lg text-navy mt-5">Smart Matching</h3>
                    <p class="text-slate text-sm mt-2 leading-relaxed">Cruzamos tu estilo de vida, espacio disponible y energía con cada mascota para sugerir el mejor encaje de adopción.</p>
                </div>

                <div class="card-hover bg-white border border-slate/10 rounded-2xl p-7">
                    <div class="w-12 h-12 rounded-xl bg-coral/10 flex items-center justify-center">
                        <i class="ph-duotone ph-flag text-coral text-2xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-lg text-navy mt-5">Reporte de extravío</h3>
                    <p class="text-slate text-sm mt-2 leading-relaxed">Publica una alerta en segundos con foto, última ubicación conocida y datos de contacto.</p>
                </div>

                <div class="card-hover bg-white border border-slate/10 rounded-2xl p-7">
                    <div class="w-12 h-12 rounded-xl bg-sky/10 flex items-center justify-center">
                        <i class="ph-duotone ph-map-trifold text-sky text-2xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-lg text-navy mt-5">Mapa de búsqueda</h3>
                    <p class="text-slate text-sm mt-2 leading-relaxed">Visualiza el punto exacto del extravío y cada avistamiento que la comunidad marca en tiempo real.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= CÓMO FUNCIONA (flujo de reporte) ================= --}}
    <section id="como-funciona" class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="max-w-xl mx-auto text-center">
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-navy">Del extravío al reencuentro</h2>
                <p class="mt-4 text-slate leading-relaxed">Tres pasos para activar la búsqueda de tu mascota apenas notas que falta.</p>
            </div>

            <div class="mt-14 grid sm:grid-cols-3 gap-8 relative">
                <div class="hidden sm:block absolute top-8 left-[16.5%] right-[16.5%] h-px bg-slate/15"></div>

                <div class="relative text-center px-4">
                    <div class="mx-auto w-16 h-16 rounded-full bg-white border border-slate/10 shadow-sm flex items-center justify-center">
                        <i class="ph-bold ph-camera text-sky text-2xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-navy mt-5">Reporta el extravío</h3>
                    <p class="text-slate text-sm mt-2 leading-relaxed">Sube una foto, describe a tu mascota y marca la última ubicación conocida.</p>
                </div>

                <div class="relative text-center px-4">
                    <div class="mx-auto w-16 h-16 rounded-full bg-white border border-slate/10 shadow-sm flex items-center justify-center">
                        <i class="ph-bold ph-qr-code text-sky text-2xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-navy mt-5">Comparte la ficha QR</h3>
                    <p class="text-slate text-sm mt-2 leading-relaxed">Descarga el PDF con el QR e imprímelo o compártelo en redes de tu zona.</p>
                </div>

                <div class="relative text-center px-4">
                    <div class="mx-auto w-16 h-16 rounded-full bg-white border border-slate/10 shadow-sm flex items-center justify-center">
                        <i class="ph-bold ph-map-pin-area text-sky text-2xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-navy mt-5">Recibe avistamientos</h3>
                    <p class="text-slate text-sm mt-2 leading-relaxed">La comunidad marca puntos en el mapa apenas alguien ve a tu mascota.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FOOTER ================= --}}
    <footer id="comunidad" class="bg-navy text-white/70">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-display font-extrabold text-lg text-white">
                    <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <i class="ph-fill ph-paw-print text-sm"></i>
                    </span>
                    Mascotas Conectadas
                </a>
                <p class="text-sm mt-4 leading-relaxed max-w-xs">Una red de adopción y reencuentro de mascotas, construida por y para la comunidad.</p>
                <div class="flex gap-3 mt-6 text-lg">
                    <a href="#" class="hover:text-white transition-colors"><i class="ph-bold ph-instagram-logo"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="ph-bold ph-facebook-logo"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><i class="ph-bold ph-tiktok-logo"></i></a>
                </div>
            </div>

            <div>
                <p class="text-white font-semibold text-sm">Producto</p>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('adopciones.index') }}" class="hover:text-white transition-colors">Adoptar</a></li>
                    <li><a href="#servicios" class="hover:text-white transition-colors">Reportar extravío</a></li>
                    <li><a href="#servicios" class="hover:text-white transition-colors">Smart Matching</a></li>
                    <li><a href="#servicios" class="hover:text-white transition-colors">Mapa de búsqueda</a></li>
                </ul>
            </div>

            <div>
                <p class="text-white font-semibold text-sm">Compañía</p>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Sobre nosotros</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Refugios aliados</a></li>
                    <li><a href="{{ route('contacto') }}" class="hover:text-white transition-colors">Contacto</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <p>&copy; {{ date('Y') }} Mascotas Conectadas. Todos los derechos reservados.</p>
                <p>Hecho con <i class="ph-fill ph-heart text-coral"></i> para reunir familias.</p>
            </div>
        </div>
    </footer>

</body>
</html>