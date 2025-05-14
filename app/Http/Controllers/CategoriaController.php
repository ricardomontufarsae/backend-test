<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index()
    {
        $categorias = Auth::user()
            ->categoria()
            ->get();

        return response()->json($categorias);
    }


    public function store(Request $request){
        $request->validate([
            'nombre' => 'required|string|unique:categorias',
            'descripcion' => 'required|string',
            'codigo' => 'required|string|unique:categorias'
        ]);

        $user = Auth::user();

        $categoria = Categoria::create([
            'user_id' => $user->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'codigo' => $request->codigo
        ]);


        return response()->json([
            'message' => 'Categoria creada correctamente',
            'categoria' => $categoria
        ]);

    }

    public function update(Request $request, $id){
        $user = Auth::user();
        $categoria = Categoria::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:categorias',
            'descripcion' => 'required|string',
            'codigo' => 'required|string|unique:categorias,codigo' . $categoria->id,
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'codigo' => $request->codigo
        ]);

        return response()->json([
            'message' => 'Categoria actualizada correctamente',
            'categoria' => $categoria
        ]);
    }

    public function destroy($id){
        $user = Auth::user();

        $categoria = Categoria::where('user_id', $user->id)->findOrFail($id);
        $categoria->delete();

        return response()->json([
            'message' => 'Categoria eliminada correctamente.'
        ]);
    }
}
