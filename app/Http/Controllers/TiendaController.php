<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tienda;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
                'logo_tienda' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'banner_tienda' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'calificacion_tienda' => 'nullable|integer',
                'categorias' => 'array|required', //ej:[1,2,3]
            ]);

            $logoUpload = null;
            $bannerUpload = null;
            
            // Subir logo
            if ($request->hasFile('logo_tienda')) {
                $logoUpload = Cloudinary::upload($request->file('logo_tienda')->getRealPath(), [
                    'folder' => 'tiendas/logos'
                ]);
            }

            // Subir banner
            if ($request->hasFile('banner_tienda')) {
                $bannerUpload = Cloudinary::upload($request->file('banner_tienda')->getRealPath(), [
                    'folder' => 'tiendas/banners'
                ]);
            }

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
                'logo_public_id' => $logoUpload?->getPublicId(),
                'logo_tienda' => $logoUpload?->getSecurePath(),
                'banner_public_id' => $bannerUpload?->getPublicId(),
                'banner_tienda' => $bannerUpload?->getSecurePath(),
                'calificacion_tienda' => $request->calificacion_tienda ?? 0,
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
                'logo_tienda' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'banner_tienda' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'calificacion_tienda' => 'nullable|integer',
                'categorias' => 'sometimes|array',
            ]);
            // Si el logo nuevo llega, eliminamos el anterior en Cloudinary
            if ($request->hasFile('logo_tienda')) {
                if ($tienda->logo_public_id) {
                    Cloudinary::destroy($tienda->logo_public_id);
                }

                $logoUpload = Cloudinary::upload($request->file('logo_tienda')->getRealPath(), [
                    'folder' => 'tiendas/logos'
                ]);

                $tienda->logo_public_id = $logoUpload->getPublicId();
                $tienda->logo_tienda = $logoUpload->getSecurePath();
            }

            // Si el banner nuevo llega, eliminamos el anterior
            if ($request->hasFile('banner_tienda')) {
                if ($tienda->banner_public_id) {
                    Cloudinary::destroy($tienda->banner_public_id);
                }

                $bannerUpload = Cloudinary::upload($request->file('banner_tienda')->getRealPath(), [
                    'folder' => 'tiendas/banners'
                ]);

                $tienda->banner_public_id = $bannerUpload->getPublicId();
                $tienda->banner_tienda = $bannerUpload->getSecurePath();
            }

            // Actualizar datos de texto
            $tienda->update($request->except(['logo_tienda', 'banner_tienda']));

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
            if ($tienda->logo_public_id) {
                Cloudinary::destroy($tienda->logo_public_id);
            }

            if ($tienda->banner_public_id) {
                Cloudinary::destroy($tienda->banner_public_id);
            }
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
