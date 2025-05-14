<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index()
    {
        $user = Auth::user();

        $productos = Producto::with(['categoria:id,nombre']) 
        ->where('user_id', $user->id)
            ->get();

        return response()->json($productos);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'fecha_ingreso' => 'nullable|date',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $user = Auth::user();

        if ($request->categoria_id) {
            $categoriaValida = Categoria::where('id', $request->categoria_id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$categoriaValida) {
                return response()->json(['message' => 'Categoría inválida'], 403);
            }
        }

        $producto = Producto::create([
            'user_id' => $user->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'codigo' => $request->codigo,
            'fecha_ingreso' => $request->fecha_ingreso,
            'categoria_id' => $request->categoria_id,
        ]);

        return response()->json([
            'message' => 'Producto creado correctamente.',
            'producto' => $producto
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $producto = Producto::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $producto->id,
            'fecha_ingreso' => 'nullable|date',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'codigo' => $request->codigo,
            'fecha_ingreso' => $request->fecha_ingreso,
            'categoria_id' => $request->categoria_id
        ]);

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'producto' => $producto
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $producto = Producto::where('user_id', $user->id)->findOrFail($id);
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente.'
        ]);
    }



}
