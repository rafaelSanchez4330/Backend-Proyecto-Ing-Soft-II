<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Herramienta;
use App\CategoriaHerramienta;

class HerramientaController extends Controller
{
    public function index()
    {
        return Herramienta::with('categoria')->get();
    }

    public function categorias()
    {
        return CategoriaHerramienta::orderBy('nombre', 'asc')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'link' => 'nullable|string',
            'categoria' => 'nullable|string',
            'categoria_nueva' => 'nullable|string'
        ]);

        if ($request->categoria === 'none') {
            $categoria_id = null;
        }

        else if ($request->categoria === 'other') {
            $categoria = CategoriaHerramienta::firstOrCreate([
                'nombre' => $request->categoria_nueva
            ]);
            $categoria_id = $categoria->id_categoria;
        }

        else {
            $categoria_id = $request->categoria;
        }

        $herramienta = Herramienta::create([
            'nombre' => $request->nombre,
            'link' => $request->link,
            'id_categoria' => $categoria_id
        ]);

        return response()->json($herramienta, 201);
    }

    public function destroy($id)
    {
        Herramienta::where('id_herramienta', $id)->delete();
        return response()->json(['ok' => true]);
    }
}
