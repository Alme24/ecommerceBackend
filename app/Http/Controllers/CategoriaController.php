<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CategoriaController extends Controller
{
    /**
     * Mostrar todas las categorias
     */
    public function index()
    {
        try {
            return response()->json(Categoria::with('tiendas')->get());
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Crear un nueva caregoria
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_categoria' => 'required|string|max:40',
                'tiendas' => 'nullable|array',
            ]);

            $categoria = Categoria::create($request->only('nombre_categoria'));
            if ($request->has('tiendas')) {
                $categoria->tiendas()->attach($request->tiendas);
            }
            return response()->json($categoria->load('tiendas'), 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validacion',
                'error'=> $e->validator->errors(),
            ],422);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Mostrar una categoria especifica
     */
    public function show(string $id)
    {
        try {
            $categoria = Categoria::with('tiendas')->findOrFail($id);
            return response()->json($categoria);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoria no encontrada',
            ],404);    
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Actualizar una categoria
     */
    public function update(Request $request, string $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $request->validate([
                'nombre_categoria' => 'sometimes|string|max:40',
                'tiendas' => 'nullable|array',
            ]);

            $categoria->update($request->only('nombre_categoria'));
            if ($request->has('tiendas')) {
                $categoria->tiendas()->sync($request->tiendas);
            }
            return response()->json($categoria->load('tiendas'));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoria no encontrada',
            ],404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error en la validacion',
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->tiendas()->detach();
            $categoria->delete();

            return response()->json(['message' => 'Categoría eliminada correctamente']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoria no encontrada',
            ],404);    
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }
}
