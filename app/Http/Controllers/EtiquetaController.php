<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etiqueta;
use App\Models\Producto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class EtiquetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json(Etiqueta::with('productos')->get());
        } catch (\Exception $e) {
            return response()->json([
                "error"=> $e->getMessage(),
            ],400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_etiqueta' => 'required|string|max:100',
                'productos'=> 'nullable|array',
            ]);

            $etiqueta = Etiqueta::create([
                'nombre_etiqueta'=> $request->nombre_etiqueta,
            ]);
            if ($request->has('productos')) {
                $etiqueta->productos()->attach($request->productos);
            }
            return response()->json($etiqueta->load('productos'), 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validacion',
                'error' => $e->validator->errors()
            ],422);
        } catch (\Exception $e){
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $etiqueta = Etiqueta::with('productos')->findOrFail($id);
            return response()->json($etiqueta);
        } catch (ModelNotFoundException $e) {
            return response()->json([ 'error' => 'Etiqueta no encontrada'],404);
        } catch(\Exception $e){
            return response()->json([
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $etiqueta = Etiqueta::findOrFail($id);

            $validated = $request->validate([
                'nombre_etiqueta' => 'sometimes|string|max:100',
                'productos' => 'nullable|array',
            ]);

            $etiqueta->update($request->all());
            if ($request->has('productos')) {
                $etiqueta->productos()->sync($request->productos);
            }
            return response()->json($etiqueta->load('productos'));
        }catch(ModelNotFoundException $e) {
            return response()->json([
                'message'=> 'Etiqueta no encontrada',
            ],404);
        } catch(ValidationException $e) {
            return response()->json([
                'message' => 'Error de validacion',
                'error' => $e->validator->errors(),
            ],500);
        } catch(\Exception $e){
            return response()->json([
                'message'=> 'Error inesperado',
                'error'=> $e->getMessage(),
            ],422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $etiqueta = Etiqueta::findOrFail($id);
            $etiqueta->productos()->detach();
            $etiqueta->delete();

            return response()->json(['message' => 'Etiqueta eliminada'],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Etiqueta no encontrada',
            ],404);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Mostrar todas las etiquetas de un producto específico
     */
    public function getByProducto($producto_id)
    {
        try {
            $producto = Producto::with('etiquetas')->findOrFail($producto_id);
            return response()->json($producto->etiquetas);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
            ],500);
        }
    }

}
