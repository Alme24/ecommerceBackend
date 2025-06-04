<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use App\Models\Producto;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ResenaController extends Controller
{
    /**
     * Listar todas las resenas
     */
    public function index()
    {
        try {
            return response()->json(Resena::all());
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Crear una nueva resena
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'pedido_id' => 'required|exists:pedidos,id',
                'descripcion_resena' => 'required|string|max:100',
                'calificacion_resena' => 'required|integer',
                'fecha_resena' => 'required|date',
            ]);

            $resena = Resena::create($request->all());

            return response()->json($resena, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validacion',
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
     * Mostrar una resena especifica
     */
    public function show(string $id)
    {
        try {
            $resena = Resena::findOrFail($id);
            return response()->json($resena);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message'=> 'Resena no encontrada',
            ],404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Actualiza una resena
     */
    public function update(Request $request, string $id)
    {
        try {
            $resena = Resena::findOrFail($id);
            $request->validate([
                'pedido_id' => 'sometimes|exists:pedidos,id',
                'descripcion_resena' => 'sometimes|string|max:100',
                'calificacion_resena' => 'sometimes|integer',
                'fecha_resena' => 'sometimes|date',
            ]);

            $resena->update($request->all());

            return response()->json($resena);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resena no encontrada',
            ],404);
        } catch (ValidationException $e) {
            return response()->json([
                'message'=> 'Error de validacion',
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
     * Eliminar una resena
     */
    public function destroy(string $id)
    {
        try {
            $resena = Resena::findOrFail($id);
            $resena->delete();
            return response()->json(['message' => 'Reseña eliminada correctamente']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message'=> 'Resena no encontrada',
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * ver las resenas de un producto
     */
    public function getResenasByProducto($producto_id)
    {
        try {
            $producto = Producto::with('pedidos.resenas.pedido.usuario')->findOrFail($producto_id);

            $resenas = collect();

            foreach ($producto->pedidos as $pedido) {
                foreach ($pedido->resenas as $resena) {
                    $usuario = $pedido->usuario;
                    $resenas->push([
                        'nombre_user' => $pedido->usuario->nombre_user ?? 'No disponible',
                        'apellido_user' => $pedido->usuario->apellido_user ?? 'No disponible',
                        'descripcion_resena' => $resena->descripcion_resena,
                        'fecha_resena' => $resena->fecha_resena,
                    ]);
                }
            }

            if ($resenas->isEmpty()) {
                return response()->json(['message' => 'Reseñas no encontradas'], 404);
            }

            return response()->json($resenas->sortByDesc('fecha_resena')->values());
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
