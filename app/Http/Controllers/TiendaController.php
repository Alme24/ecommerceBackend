<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tienda;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class TiendaController extends Controller
{
    /**
     * Muestra una lista de todas las tiendas
     */
    public function index()
    {
        try {
            return response()->json(Tienda::with('categorias')->get());
            //return response()->json(Tienda::all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
                ],500);
        }
    }

    /**
     * Guarda una tienda en la base de datos
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'usuario_id' => 'required|exists:usuarios,id',
                'nombre_tienda' => 'required|string|max:40',
                'descripcion_tienda' => 'required|string|max:500',
                'telefono_tienda' => 'required|string|max:40',
                'direccion_tienda' => 'required|string|max:50',
                'ubicacion_tienda' => 'required|string',
                'ciudad_tienda' => 'required|string|max:40',
                'provincia_tienda' => 'required|string|max:40',
                'lugarEntregas_tienda' => 'required|string|max:50',
                'logo_tienda' => 'required|string',
                'banner_tienda' => 'required|string',
                'calificacion_tienda' => 'nullable|integer',
                'categorias' => 'array|required', //ej:[1,2,3]
            ]);

            $tienda = Tienda::create([
                'usuario_id' => $request->usuario_id,
                'nombre_tienda' => $request->nombre_tienda,
                'descripcion_tienda' => $request->descripcion_tienda,
                'telefono_tienda' => $request->telefono_tienda,
                'direccion_tienda' => $request->direccion_tienda,
                'ubicacion_tienda' => $request->ubicacion_tienda,
                'ciudad_tienda' => $request->ciudad_tienda,
                'provincia_tienda' => $request->provincia_tienda,
                'lugarEntregas_tienda' => $request->lugarEntregas_tienda,
                'logo_tienda' => $request->logo_tienda,
                'banner_tienda' => $request->banner_tienda,
                'calificacion_tienda' => $request->calificacion_tienda,
            ]);

            $tienda->categorias()->attach($request->categorias);
            return response()->json($tienda->load('categorias'), 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de la validacion',
                'error' => $e->validator->errors()
            ],422);
        }catch (\Exception $e) {
            return response()->json([
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Muestra una tienda en especifico.
     */
    public function show(string $id)
    {
        try {
            $tienda = Tienda::with('categorias')->findOrFail($id);
            return response()->json($tienda);
        } catch (ModelNotfoundException $e) {
            return response()->json(['error' => 'Tienda no encontrado'],404);
        }catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()],500);
        }
    }

    /**
     * Actualiza una tienda en la base de datos
     */
    public function update(Request $request, string $id)
    {
        try {
            $tienda = Tienda::findOrFail($id);

            $request->validate([
                'usuario_id' => 'sometimes|exists:usuarios,id',
                'nombre_tienda' => 'sometimes|string|max:40',
                'descripcion_tienda' => 'sometimes|string|max:500',
                'telefono_tienda' => 'sometimes|string|max:40',
                'direccion_tienda' => 'sometimes|string|max:50',
                'ubicacion_tienda' => 'sometimes|string',
                'ciudad_tienda' => 'sometimes|string|max:40',
                'provincia_tienda' => 'sometimes|string|max:40',
                'lugarEntregas_tienda' => 'sometimes|string|max:50',
                'logo_tienda' => 'sometimes|string',
                'banner_tienda' => 'sometimes|string',
                'calificacion_tienda' => 'nullable|integer',
                'categorias'=>'sometimes|array',
            ]);

            $tienda->update($request->all());

            if($request->has('categorias')){
                $tienda->categorias()->sync($request->categorias);
            }
            return response()->json($tienda->load('categorias'));
        }catch (ModelNotfoundException $e) {
            return response()->json([
                'message' => 'Tienda no encontrada'
            ],404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validacion',
                'error' => $e->errors(),
            ],422);
        }catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Elimina una tienda desde la base de datos.
     */
    public function destroy(string $id)
    {
        try {
            $tienda = Tienda::findOrFail($id);
            $tienda->categorias()->detach();
            $tienda->delete();
            return response()->json(['message' => 'Tienda eliminada']);
        } catch (ModelNotfoundException $e) {
            return response()->json([
                'message' => 'Tienda no encontrada',
            ],404);
        } catch (\Exception $e) {
            return response()->json([
                'error'=> $e->getMessage(),
            ],422);
        }
    }

    /**
     * 
     */
    public function getByUserId($user_id)
    {
        try {
            $tienda = Tienda::where('usuario_id',$user_id)->firstOrFail();
            return response()->json(['id'=> $tienda->id]);  
        }catch(ModelNotfoundException $e){
            return response()->json([
                'message'=>'Tienda no encontrada'
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'error'=> $e->getMessage(),
            ],500);
        }
    }
}
