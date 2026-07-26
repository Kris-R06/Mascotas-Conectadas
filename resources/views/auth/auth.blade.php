<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas Conectadas - Ingresar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-100 h-screen flex items-center justify-center p-4 font-sans text-slate-800 overflow-hidden">

    <div class="relative bg-white w-full max-w-4xl h-[700px] rounded-3xl shadow-2xl overflow-hidden">

        <div id="register-container" class="absolute top-0 left-0 w-1/2 h-full bg-white flex flex-col items-center justify-center p-8 transition-all duration-700 ease-in-out opacity-0 z-0">
            <form action="{{ route('register') }}" method="POST" class="w-full flex flex-col items-center">
                @csrf
                <h2 class="text-3xl font-bold mb-4 flex items-center gap-2">
                    <i class="ph ph-paw-print text-indigo-500"></i> Únete a la Manada
                </h2>

                @if ($errors->any() && old('name'))
                    <div class="w-full bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl mb-4 text-sm animate-pulse">
                        <strong class="font-bold flex items-center gap-1"><i class="ph ph-warning-circle text-lg"></i> Revisa estos detalles:</strong>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="w-full grid grid-cols-2 gap-4">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nombre completo" minlength="3" class="w-full bg-slate-100 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 invalid:focus:ring-red-500 transition-shadow" required>
                    
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" class="w-full bg-slate-100 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 invalid:focus:ring-red-500 transition-shadow" required>
                    
                    <input type="tel" name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono" pattern="[0-9]{10,13}" title="Debe contener entre 10 y 13 números" class="w-full bg-slate-100 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 invalid:focus:ring-red-500 transition-shadow" required>
                    
                    <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Dirección" class="w-full bg-slate-100 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 invalid:focus:ring-red-500 transition-shadow" required>
                    
                    <input type="password" name="password" placeholder="Contraseña" minlength="8" title="Mínimo 8 caracteres" class="w-full bg-slate-100 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 invalid:focus:ring-red-500 transition-shadow" required>
                    
                    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" minlength="8" class="w-full bg-slate-100 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 invalid:focus:ring-red-500 transition-shadow" required>
                </div>

                <div class="w-full flex justify-between mt-6 text-sm text-slate-600 px-2">
                    <label class="flex items-center gap-2 cursor-pointer hover:text-indigo-600 transition-colors">
                        <input type="checkbox" name="has_yard" value="1" {{ old('has_yard') ? 'checked' : '' }} class="accent-indigo-500 w-4 h-4">
                        Tengo patio amplio
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-indigo-600 transition-colors">
                        <input type="checkbox" name="kids" value="1" {{ old('kids') ? 'checked' : '' }} class="accent-indigo-500 w-4 h-4">
                        Tengo niños en casa
                    </label>
                </div>
                
                <button type="submit" class="mt-6 bg-indigo-500 text-white font-bold py-3 px-12 rounded-full hover:bg-indigo-600 transition-colors shadow-lg">Registrarse</button>
            </form>
        </div>

        <div id="login-container" class="absolute top-0 left-0 w-1/2 h-full bg-white flex flex-col items-center justify-center p-8 transition-all duration-700 ease-in-out opacity-100 z-10">
            <form action="{{ route('login') }}" method="POST" class="w-full flex flex-col items-center">
                @csrf
                <h2 class="text-3xl font-bold mb-6 flex items-center gap-2">
                    <i class="ph ph-sign-in text-indigo-500"></i> Iniciar Sesión
                </h2>

                @if ($errors->any() && !old('name'))
                    <div class="w-full bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl mb-4 text-sm animate-pulse">
                        <strong class="font-bold flex items-center gap-1"><i class="ph ph-warning-circle text-lg"></i> Datos incorrectos:</strong>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" class="w-full bg-slate-100 p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 invalid:focus:ring-red-500 transition-shadow mb-4" required>
                
                <input type="password" name="password" placeholder="Contraseña" class="w-full bg-slate-100 p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 invalid:focus:ring-red-500 transition-shadow mb-6" required>
    
                <button type="submit" class="bg-indigo-500 text-white font-bold py-3 px-12 rounded-full hover:bg-indigo-600 transition-colors shadow-lg">Ingresar</button>
            </form>
        </div>

        <div id="overlay-container" class="absolute top-0 left-1/2 w-1/2 h-full bg-indigo-500 text-white transition-transform duration-700 ease-in-out z-20 overflow-hidden shadow-2xl">
            
            <div id="overlay-left" class="absolute inset-0 flex flex-col items-center justify-center p-10 text-center transition-transform duration-700 ease-in-out -translate-x-full">
                <i class="ph ph-dog text-6xl mb-4"></i>
                <h2 class="text-3xl font-bold mb-4">¡Bienvenido de nuevo!</h2>
                <p class="mb-8 font-light text-indigo-100">Para seguir interactuando con la comunidad y el mapa, por favor inicia sesión con tus datos personales.</p>
                <button id="signInBtn" class="border-2 border-white text-white font-bold py-2 px-8 rounded-full hover:bg-white hover:text-indigo-500 transition-colors">Iniciar Sesión</button>
            </div>

            <div id="overlay-right" class="absolute inset-0 flex flex-col items-center justify-center p-10 text-center transition-transform duration-700 ease-in-out translate-x-0">
                <i class="ph ph-cat text-6xl mb-4"></i>
                <h2 class="text-3xl font-bold mb-4">¿Nuevo aquí?</h2>
                <p class="mb-8 font-light text-indigo-100">Regístrate y ayúdanos a hacer el match perfecto entre personas y mascotas.</p>
                <button id="signUpBtn" class="border-2 border-white text-white font-bold py-2 px-8 rounded-full hover:bg-white hover:text-indigo-500 transition-colors">Registrarse</button>
            </div>
        </div>

    </div>

    <script>
        const signUpBtn = document.getElementById('signUpBtn');
        const signInBtn = document.getElementById('signInBtn');
        
        const loginContainer = document.getElementById('login-container');
        const registerContainer = document.getElementById('register-container');
        const overlayContainer = document.getElementById('overlay-container');
        const overlayLeft = document.getElementById('overlay-left');
        const overlayRight = document.getElementById('overlay-right');

        // Función que ejecuta la animación hacia el Registro
        const slideToRegister = () => {
            overlayContainer.classList.add('-translate-x-full');
            
            overlayLeft.classList.remove('-translate-x-full');
            overlayLeft.classList.add('translate-x-0');
            overlayRight.classList.remove('translate-x-0');
            overlayRight.classList.add('translate-x-full');
            
            loginContainer.classList.add('translate-x-full', 'opacity-0', 'z-0');
            loginContainer.classList.remove('opacity-100', 'z-10');
            
            registerContainer.classList.add('translate-x-full', 'opacity-100', 'z-10');
            registerContainer.classList.remove('opacity-0', 'z-0');
        };

        // Event Listeners para los botones
        signUpBtn.addEventListener('click', slideToRegister);

        signInBtn.addEventListener('click', () => {
            overlayContainer.classList.remove('-translate-x-full');
            
            overlayLeft.classList.add('-translate-x-full');
            overlayLeft.classList.remove('translate-x-0');
            overlayRight.classList.add('translate-x-0');
            overlayRight.classList.remove('translate-x-full');
            
            loginContainer.classList.remove('translate-x-full', 'opacity-0', 'z-0');
            loginContainer.classList.add('opacity-100', 'z-10');
            
            registerContainer.classList.remove('translate-x-full', 'opacity-100', 'z-10');
            registerContainer.classList.add('opacity-0', 'z-0');
        });

        @if($errors->any() && old('name'))
            setTimeout(() => {
                slideToRegister();
            }, 100);
        @endif
        
    </script>
</body>
</html>