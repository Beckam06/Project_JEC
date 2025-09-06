<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Credenciales fijas para clientes (en un sistema real usarías una base de datos)
        $clientCredentials = [
            'cliente@casaamarilla.com' => 'amarilla123',
            'cliente@casanaranja.com' => 'naranja123', 
            'cliente@casaverde.com' => 'verde123'
        ];

        if (array_key_exists($request->email, $clientCredentials) && 
            $request->password === $clientCredentials[$request->email]) {
            
            // Determinar qué casa es basado en el email
            $casa = match($request->email) {
                'cliente@casaamarilla.com' => 'Casa Amarilla',
                'cliente@casanaranja.com' => 'Casa Naranja',
                'cliente@casaverde.com' => 'Casa Verde',
                default => null
            };

            session(['client_casa' => $casa, 'client_email' => $request->email]);
            
            return redirect()->route('client.requests.create');
        }

        return back()->withErrors(['email' => 'Credenciales inválidas']);
    }

    public function logout()
    {
        session()->forget(['client_casa', 'client_email']);
        return redirect()->route('client.login');
    }
}