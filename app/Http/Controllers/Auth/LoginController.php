<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login'); // Asegúrate de tener esta vista
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirigir según el rol
            if ($user->hasRole('super_admin')) {
                return redirect()->intended('/admin');
            } elseif ($user->hasRole('vendedor')) { // <-- AÑADIDO
                return redirect()->intended('/vendedor');
            } elseif ($user->hasRole('cliente')) {
                return redirect()->intended('/cliente');
            }
            
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'No tienes un rol asignado para acceder.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}