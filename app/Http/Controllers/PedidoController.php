<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    /**
     * Lista de todos los pedidos
     */
    public function index()
    {
        try {
            return response()->json(Pedido::all());
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }


    /**
     * Crear un nuevo pedido.
     */
    public function store(Request $request)
    {
        try {
            $pedido = Pedido::create($request->validate([
                'producto_id' => 'required|exists:productos,id',
                'carrito_id' => 'required|exists:carritos,id',
                'user_id' => 'required|exists:usuarios,id',
                'unidad_pedido' => 'required|integer|min:1',
                'preciosub_pedido' => 'required|numeric|min:0',
                'estado_pedido' => 'nullable|integer|min:0',
                'fecha_pedido' => 'nullable|date',
                'fechaFin_pedido' => 'nullable|date',
            ]));

            return response()->json($pedido, 201);
        }catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error en validacion',
                'error' => $e->validator->errors(),
            ],422);
        }
    }

    /**
     * Mostrar pedido
     */
    public function show(string $id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            return response()->json($pedido);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message'=> 'Pedido no encontrado',
            ],404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Actualizar pedido
     */
    public function update(Request $request, string $id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->update($request->validate([
                'producto_id' => 'sometimes|exists:productos,id',
                'carrito_id' => 'sometimes|exists:carritos,id',
                'user_id' => 'sometimes|exists:usuarios,id',
                'unidad_pedido' => 'sometimes|integer|min:1',
                'preciosub_pedido' => 'sometimes|numeric|min:0',
                'estado_pedido' => 'nullable|integer|min:0',
                'fecha_pedido' => 'nullable|date',
                'fechaFin_pedido' => 'nullable|date',
            ]));

            return response()->json($pedido);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pedido no encontrado',
            ],404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error en validacion',
                'error' => $e->validator->errors(),
            ],422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Eliminar pedido
     */
    public function destroy(string $id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->delete();
            return response()->json(['message' => 'Pedido eliminado correctamente']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pedido no encontrado',
            ],404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Lista de pedidos para artesano(creador)
     */
    public function pedidosEstadoArtesano($tienda_id, $estado_pedido)
    {
        try {
            $productos = Producto::where('tienda_id', $tienda_id)
                ->whereHas('pedidos', function ($query) use ($estado_pedido){
                    $query->where('estado_pedido', $estado_pedido);
                })
                ->with(['pedidos' => function ($query) use ($estado_pedido) {
                    $query->where('estado_pedido', $estado_pedido);
                }])
                ->get();

            if ($productos->isEmpty()) {
                return response()->json(['message' => 'No hay productos con pedidos en esta tienda'], 404);
            }

            return response()->json($productos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Lista de pedidos por comprador
     */
    public function pedidosEstadoUser($user_id, $estado_pedido)
    {
        try {
            $pedidos = Pedido::where('user_id', $user_id)
                    ->where('estado_pedido', $estado_pedido)
                    ->with(['producto'])
                    ->get();

            if ($pedidos->isEmpty()) {
                return response()->json(['message' => 'No hay pedidos confirmados para este usuario'], 404);
            }

            return response()->json($pedidos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Lista de pedidos de un carrito
     */
    public function pedidosPorCarrito($carrito_id)
    {
        try {
            $pedidos=Pedido::where('carrito_id',$carrito_id)
                    ->with('producto')
                    ->get();
            if($pedidos->isEmpty()){
                return response()->json(['message'=>'No hay pedidos para este carrito.'],404);     
            }

            return response()->json($pedidos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }
}
