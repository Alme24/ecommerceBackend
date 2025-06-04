<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CarritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        try {
            return response()->json(Carrito::all());
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'modoPago_carrito' => 'required|string|max:50',
                'precioTotal_carrito' => 'required|numeric|min:0',
            ]);

            $carrito = Carrito::create($request->only(['modoPago_carrito', 'precioTotal_carrito']));
            return response()->json($carrito, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error en validacion',
                'error' => $e->validator->errors(),
            ],422);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $carrito = Carrito::findOrFail($id);
            return response()->json($carrito);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=> 'Carrito no encontrado'],404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $carrito = Carrito::findOrFail($id);
            $request->validate([
                'modoPago_carrito' => 'sometimes|required|string|max:50',
                'precioTotal_carrito' => 'sometimes|required|numeric|min:0',
            ]);
            
            $carrito->update($request->only(['modoPago_carrito', 'precioTotal_carrito']));
            return response()->json($carrito);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=> 'Carrito no encontrado'],404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error en validacion',
                'error' => $e->validator->errors(),
            ],422);
        }catch (\Exception $e) {
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
        try{
            $carrito = Carrito::findOrFail($id);

            $carrito->delete();
            return response()->json(['message' => 'Carrito eliminado correctamente']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=> 'Carrito no encontrado'],404);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }
}
