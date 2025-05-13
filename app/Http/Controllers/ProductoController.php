<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function index()
    {
        $user = Auth::user();
        $productos = Producto::where('user_id', $user->id)->get();
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
        ]);

        $user = Auth::user();

        $producto = Producto::create([
            'user_id' => $user->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'codigo' => $request->codigo,
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
        ]);

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'codigo' => $request->codigo,
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
