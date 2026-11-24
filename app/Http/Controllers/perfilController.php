<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class perfilController extends Controller
{
    public function index()
    {
        

        $fechaCreacion = Auth::User()->created_at;
        $fechaFormateada = $fechaCreacion->format('d/m/Y');

        return view('perfil.index')->with('fechaFormateada', $fechaFormateada);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    
}
