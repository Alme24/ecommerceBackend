<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ProductoController extends Controller
{
    /**
     * Mostrar todos los productos
     */
    public function index()
    {
        try {
            return response()->json(Producto::with('etiquetas')->get(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Crear un nuevo producto
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tienda_id' => 'required|exists:tiendas,id',
                'categoria_id' => 'required|exists:categorias,id',
                'nombre_producto' => 'required|string|max:50',
                'descripcion_producto' => 'nullable|max:200',
                'tamano_producto' => 'nullable',
                'peso_producto' => 'nullable',
                'precio_producto' => 'required|numeric',
                'color_producto' => 'nullable',
                'cantDisp_producto' => 'required|integer',
                'descuento_producto' => 'nullable|numeric',
                'etiquetas' => 'nullable|array',
            ]);
            
            $producto = Producto::create([
                'tienda_id' => $request->tienda_id,
                'categoria_id' => $request->categoria_id,
                'nombre_producto' => $request->nombre_producto,
                'descripcion_producto' => $request->descripcion_producto,
                'tamano_producto' => $request->tamano_producto,
                'peso_producto' => $request->peso_producto,
                'precio_producto' => $request->precio_producto,
                'color_producto' => $request->color_producto,
                'cantDisp_producto' => $request->cantDisp_producto,
                'descuento_producto' =>$request->descuento_producto,
            ]);
            $producto->etiquetas()->attach($request->etiquetas);

            return response()->json($producto, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validacion',
                'error' => $e->validator->errors(),
            ],422);
        }catch (\Exception $e) {
            return response()->json([
                'message'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Mostrar un producto en especifico
     */
    public function show(string $id)
    {
        try {
            $producto = Producto::with('etiquetas')->findOrFail($id);
            return response()->json($producto);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Producto no encontrado'],404);
        }catch (Exception $e) {
            return response()->json([
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, string $id)
    {
        try {
            $producto = Producto::findOrFail($id);
        
            $request->validate([
                'tienda_id' => 'sometimes|exists:tiendas,id',
                'categoria_id' => 'sometimes|exists:categorias,id',
                'nombre_producto' => 'sometimes|string|max:50',
                'descripcion_producto' => 'sometimes|max:200',
                'tamano_producto' => 'sometimes',
                'peso_producto' => 'sometimes',
                'precio_producto' => 'sometimes|numeric',
                'color_producto' => 'sometimes',
                'cantDisp_producto' => 'sometimes|integer',
                'descuento_producto' => 'nullable|numeric',
                'etiquetas' => 'sometimes|array',
            ]);
            $producto->update($request->all());
            
            if ($request->has('etiquetas')) {
                $producto->etiquetas()->sync($request->etiquetas);
            }

            return response()->json($producto->load('etiquetas'));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message'=>'Producto no encontrado',
                ],404);
        }catch(ValidationException $e) {
            return response()->json([
                'message'=> 'Error de validacion',
                'error'=> $e->validator->errors()
            ],422);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Eliminar producto
     */
    public function destroy(string $id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->etiquetas()->detach();
            $producto->delete();
            return response()->json(['message' => 'Producto eliminado']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Producto no encontrado'],404);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Lista de productos por categoria
     */
    public function getByCategoria($categoria_id)
    {
        try {
            $productos = Producto::where('categoria_id', $categoria_id)->get();

        if ($productos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron productos para esta categoría.'], 404);
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
     * Detalle de producto para resena
     */
    public function getProductoDetall(string $id)
    {
        try {
            $producto = Producto::findOrFail($id);
        return response()->json([
            'nombre_producto' => $producto->nombre_producto,
            'descripcion_producto' => $producto->descripcion_producto ?? '',
        ]);
        }catch(ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Producto no encontrado',
            ],404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
            ],500);
        }
    }
}
