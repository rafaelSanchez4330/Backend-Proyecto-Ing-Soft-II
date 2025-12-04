<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caso;
use App\Herramienta;
use App\CategoriaHerramienta;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $casos = Caso::orderBy('fecha_creacion', 'desc')->take(10)->get();
        $herramientas = Herramienta::all();
        $categorias = CategoriaHerramienta::all();
        $user = Auth::user();

        return view('dashboard', compact('casos', 'herramientas', 'categorias', 'user'));
    }
}
