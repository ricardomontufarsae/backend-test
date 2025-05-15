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

        try{
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

        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la categoria.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function update(Request $request, $id){
        try{
            $user = Auth::user();
            $categoria = Categoria::where('user_id', $user->_id)->findOrFail($id);

            $request->validate([
                'nombre' => 'required|string|unique:categorias',
                'descripcion' => 'required|string',
                'codigo' => 'required|string',
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

        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al editar la categoria.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id){
        try{
            $user = Auth::user();

            $categoria = Categoria::where('user_id', $user->_id)->findOrFail($id);
            $categoria->delete();

            return response()->json([
                'message' => 'Categoria eliminada correctamente.'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la categoria.',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
