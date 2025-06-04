<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios
     */
    public function index()
    {
        try {
            $usuarios = Usuario::with('typeuser')->get();
            return response()->json($usuarios, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error'=> $e->getMessage()
            ],400);
        }
    }

    /**
     * Guarda un nuevo recurso en la base de datos
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'typeuser_id' => 'required',
                'nombre_user' => 'required|string|max:255',
                'apellido_user' => 'required|string|max:255',
                'email_user' => 'required|email|unique:usuarios,email_user',
                'contrasena_user' => 'required|string',
            ]);

            $usuario = Usuario::create([
                'typeuser_id' => $request->typeuser_id,
                'nombre_user' => $request->nombre_user,
                'apellido_user' => $request->apellido_user,
                'email_user' => $request->email_user,
                'contrasena_user' => Hash::make($request->contrasena_user),
            ]);

            return response()->json($usuario, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'error'=> $e->validator->errors()
                ],422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Ver usuario
     */
    public function show(string $id)
    {
        try {
            $usuario = Usuario::with('typeuser')->findOrFail($id);
            return response()->json($usuario);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error'=> 'Usuario no encontrado'],404);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Guarda los cambios del usuario editado en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            $request->validate([
                'typeuser_id' => 'sometimes',
                'nombre_user' => 'sometimes|string|max:255',
                'apellido_user' => 'sometimes|string|max:255',
                'email_user' => 'sometimes|email|unique:usuarios,email_user,' . $usuario->id,
                'contrasena_user' => 'sometimes|string',
            ]);
            $usuario->update($request->all());
            return response()->json($usuario);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Usuario no encontrado',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Elimina un usuario especifico
     */
    public function destroy(string $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();
            return response()->json(['message' => 'Usuario eliminado correctamente'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'menssage' => 'Usuario no encontrado',
            ], 404);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Cambiar contrasena de un usuario
     */
    public function cambiarContrasena(Request $request, $id)
    {
        try {
            $request->validate([
                'contrasena_actual' => 'required',
                'nueva_contrasena' => 'required|confirmed',
            ]);

            $usuario = Usuario::findOrFail($id);

            if (!Hash::check($request->contrasena_actual, $usuario->contrasena_user)) {
                return response()->json(['error' => 'La contraseña actual no es correcta'], 401);
            }

            $usuario->contrasena_user = Hash::make($request->nueva_contrasena); 
            $usuario->save();

            return response()->json(['message' => 'Contraseña actualizada correctamente']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Usuario no encontrado',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

}
