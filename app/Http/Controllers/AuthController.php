<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TipoUser;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.auth');
    }

    public function register(Request $request)
    {
        // 1. Validamos los campos recibidos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'required|string|max:13|min:10',
            'direccion' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Aseguramos que el rol por defecto exista en la base de datos
        $tipoUser = TipoUser::firstOrCreate(
            ['nombre' => 'Usuario']
        );

        // 3. Creamos al usuario de forma segura
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'telefono' => $validatedData['telefono'],
            'direccion' => $validatedData['direccion'],
            'has_yard' => $request->has('has_yard'),
            'kids' => $request->has('kids'),
            'tipo_user_id' => $tipoUser->id, 
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }

    public function login()
    {
        $credentials = request()->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Las credenciales son incorrectas.',
        ]);    
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
