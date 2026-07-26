<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto — Mascotas Conectadas</title>
    <meta name="description" content="Contáctanos para dudas, sugerencias o ayuda con Mascotas Conectadas.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

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

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Sora', sans-serif; }
    </style>
</head>
<body class="bg-white text-navy antialiased flex flex-col min-h-screen">

    {{-- ================= NAVBAR (De la Landing Page) ================= --}}
    <header x-data="{ open: false }" class="sticky top-0 z-50 bg-sky/20 backdrop-blur-md border-b border-slate/10">
        <nav class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-display font-extrabold text-xl text-navy">
                <span class="w-9 h-9 rounded-xl bg-sky/10 flex items-center justify-center">
                    <i class="ph-fill ph-paw-print text-sky text-lg"></i>
                </span>
                Mascotas Conectadas
            </a>

            <div class="hidden lg:flex items-center gap-6 ml-auto">
                <div class="flex items-center gap-5 text-sm font-medium text-navy/80">
                    <a href="{{ url('/#servicios') }}" class="hover:text-sky transition-colors">Reportar extravío</a>
                    <a href="{{ url('/#como-funciona') }}" class="hover:text-sky transition-colors">Cómo funciona</a>
                    <a href="{{ route('contacto') }}" class="text-sky font-bold transition-colors">Contactanos</a>
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
            <a href="{{ url('/#servicios') }}" class="block text-black font-medium">Reportar extravío</a>
            <a href="{{ url('/#como-funciona') }}" class="block text-black font-medium">Cómo funciona</a>
            <hr class="border-slate/10">
            @auth
                <a href="{{ route('home') }}" class="block text-navy font-semibold">Ir al panel</a>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="block text-coral font-semibold">Iniciar sesión</a>
            @endguest
        </div>
    </header>

    {{-- ================= CONTENIDO DE CONTACTO ================= --}}
    <main class="w-full flex-grow bg-white p-6 md:p-10 flex flex-col items-center justify-center relative">
        
        <div class="absolute inset-0 bg-[radial-gradient(#1F6FEB_1px,transparent_1px)] [background-size:20px_20px] opacity-[0.03] pointer-events-none"></div>

        <div class="max-w-2xl w-full text-center relative z-10 py-10">
            <h1 class="font-display font-extrabold text-4xl sm:text-5xl text-navy mb-5">
                ¡¡Contactanos!!
            </h1>
            <p class="text-lg text-slate mb-10 leading-relaxed px-4">
                Eres un refugio y quieres darte de alta en la plataforma, o eres un usuario y quieres reportar un problema, duda o sugerencia. ¡Estamos aquí para ayudarte!
            </p>

            <div class="bg-pastel/50 p-8 md:p-10 rounded-3xl border border-sky/10 shadow-lg shadow-sky/5 inline-block w-full sm:w-auto transform hover:-translate-y-1 transition-transform duration-300">
                <p class="text-xs font-bold text-slate uppercase tracking-wider mb-3">Escríbenos a nuestro correo oficial</p>
                
                <a href="mailto:christoramos0604@gmail.com" class="font-display font-bold text-2xl md:text-3xl text-sky hover:text-navy transition-colors flex items-center justify-center gap-3">
                    <i class="ph-bold ph-envelope-simple-open"></i> christoramos0604@gmail.com
                </a>
            </div>
        </div>
    </main>

    {{-- ================= FOOTER (De la Landing Page) ================= --}}
    <footer id="comunidad" class="bg-navy text-white/70 mt-auto">
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
                    <li><a href="{{ url('/#servicios') }}" class="hover:text-white transition-colors">Reportar extravío</a></li>
                    <li><a href="{{ url('/#servicios') }}" class="hover:text-white transition-colors">Smart Matching</a></li>
                    <li><a href="{{ url('/#servicios') }}" class="hover:text-white transition-colors">Mapa de búsqueda</a></li>
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