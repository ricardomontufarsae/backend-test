<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use MongoDB\BSON\ObjectId;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $facturas = Factura::where('user_id', $user->_id)
            ->orderBy('fecha_factura', 'desc')
            ->get();

        return response()->json($facturas);

    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'emisor' => 'required|string',
                'receptor' => 'required|string',
                'numero_factura' => 'required|string|unique:facturas,numero_factura',
                'fecha_factura' => 'required|date',
                'productos' => 'required|array|min:1',
                'productos.*.producto_id' => 'required|string',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio_unitario' => 'required|numeric|min:0',
            ]);

            $productosDetalle = [];
            $monto_total = 0;

            foreach ($request->productos as $item) {
                $prodId = new ObjectId($item['producto_id']);

                $producto = Producto::where('_id', $prodId)
                    ->where('user_id', $user->_id)
                    ->first();

                $categoria = $producto->categoria;

                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $monto_total += $subtotal;

                $productosDetalle[] = [
                    'producto_id' => $item['producto_id'],
                    'nombre' => $producto->nombre,
                    'categoria_producto' => $categoria ? $categoria->nombre : 'Sin categoría',
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal,
                ];
            }

            $factura = Factura::create([
                'emisor' => $request->emisor,
                'receptor' => $request->receptor,
                'numero_factura' => $request->numero_factura,
                'fecha_factura' => $request->fecha_factura,
                'user_id' => $user->_id,
                'monto_factura' => $monto_total,
                'productos' => $productosDetalle,
            ]);

            return response()->json([
                'message' => 'Factura creada exitosamente.',
                'factura' => $factura
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Error de validacion', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la factura.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();

            try {
                $facturaId = new ObjectId($id);
            } catch (\Exception $e) {
                return response()->json(['message' => 'ID de factura invalido.'], 400);
            }

            $factura = Factura::where('_id', $facturaId)
                ->where('user_id', $user->_id)
                ->first();


            $request->validate([
                'emisor' => 'required|string',
                'receptor' => 'required|string',
                'numero_factura' => 'required|string|unique:facturas,numero_factura,'.$factura->_id.',_id',
                'fecha_factura' => 'required|date',
                'productos' => 'required|array|min:1',
                'productos.*.producto_id' => 'required|string',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio_unitario' => 'required|numeric|min:0',
            ]);

            $productosDetalle = [];
            $monto_total = 0;

            foreach ($request->productos as $item) {
                $prodId = new ObjectId($item['producto_id']);

                $producto = Producto::where('_id', $prodId)
                    ->where('user_id', $user->_id)
                    ->first();

                $categoria = $producto->categoria;

                if (!$producto) {
                    throw ValidationException::withMessages([
                        'productos' => ['El producto no existe o no pertenece al usuario.']
                    ]);
                }

                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $monto_total += $subtotal;

                $productosDetalle[] = [
                    'producto_id' => $item['producto_id'],
                    'nombre' => $producto->nombre,
                    'categoria_producto' => $categoria ? $categoria->nombre : 'Sin categoría',
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal,
                ];
            }

            $factura->update([
                'emisor' => $request->emisor,
                'receptor' => $request->receptor,
                'numero_factura' => $request->numero_factura,
                'fecha_factura' => $request->fecha_factura,
                'monto_factura' => $monto_total,
                'productos' => $productosDetalle,
            ]);

            return response()->json([
                'message' => 'Factura actualizada exitosamente.',
                'factura' => $factura
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la factura.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try{
            $user = Auth::user();

            $facturas = Factura::where('user_id', $user->_id)->findOrFail($id);
            $facturas->delete();

            return response()->json([
                'message' => 'Factura eliminada correctamente.'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error la factura el producto.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function productosFacturados()
    {
        $user = Auth::user();

        $facturas = Factura::where('user_id', $user->_id)->get();

        $productosFacturados = [];

        foreach ($facturas as $factura) {
            foreach ($factura->productos as $producto) {
                $productosFacturados[] = [
                    'nombre' => $producto['nombre'],
                    'categoria_producto' => $producto['categoria_producto'],
                    'numero_factura' => $factura->numero_factura,
                    'fecha_factura' => $factura->fecha_factura,
                    'precio_total' => $producto['cantidad'] * $producto['precio_unitario']
                ];
            }
        }

        return response()->json($productosFacturados);
    }

    public function show($facturaNum)
    {
        try {
            $user = Auth::user();

            $factura = Factura::where('numero_factura', $facturaNum)
                ->where('user_id', $user->_id)
                ->first();

            if (!$factura) {
                return response()->json(['message' => 'Factura no encontrada.'], 404);
            }

            return response()->json($factura);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la factura.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
