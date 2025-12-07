<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caso;
use App\Usuario;
use Illuminate\Support\Facades\Auth;

class ConsultorController extends Controller
{
    public function inicio()
    {
        return view('consultor.inicio');
    }

    public function usuariosIndex()
    {
        $usuarios = Usuario::where('activo', true)->get();
        return view('consultor.usuarios.lista-usuarios', compact('usuarios'));
    }

    public function usuariosShow($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('consultor.usuarios.detalle-usuario', compact('usuario'));
    }

    public function casosIndex()
    {
        // Assuming we want to show all cases or cases assigned to the consultor?
        // The original JS fetched '/api/consultor/casos'.
        // Let's check what that API returned. 
        // For now, I'll fetch all cases or maybe filter by something if needed.
        // If the consultor sees all cases:
        $casos = Caso::with('creador')->get();
        return view('consultor.casos.lista-casos', compact('casos'));
    }

    public function casosShow($id)
    {
        $caso = Caso::with(['creador', 'evidencias'])->findOrFail($id);
        return view('consultor.casos.detalle-caso', compact('caso'));
    }

    public function usuariosCasosRelacionados($id)
    {
        // Obtener el usuario o lanzar 404
        $usuario = Usuario::findOrFail($id);

        // Obtener los casos creados por el usuario
        $casos = $usuario->casosCreados;  // RELACIÓN YA DEFINIDA

        //Retornar vista casos-relacionados, junto a las variables usuario y casos
        return view('consultor.usuarios.casos-relacionados', compact('usuario', 'casos'));
    }
}
