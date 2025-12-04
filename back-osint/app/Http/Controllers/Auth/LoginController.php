<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required'],
            'contrasena' => ['required'],
        ]);

        // Map 'usuario' and 'contrasena' to 'email'/'username' and 'password' if needed
        // Assuming the User model uses 'usuario' and 'password' or similar.
        // Let's check the User model or migration to be sure about the fields.
        // Based on COMO_USAR_LOGIN.md, fields are 'usuario' and 'contrasena'.
        // Standard Laravel Auth expects 'email' and 'password'.
        // We might need to manually attempt auth.

        if (Auth::attempt(['usuario' => $credentials['usuario'], 'password' => $credentials['contrasena']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('usuario');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
