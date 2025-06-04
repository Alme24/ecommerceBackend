<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CuentaController extends Controller
{
    /**
     * Muestra una lista de todos los cuentas
     */
    public function index()
    {
        try {
            return Cuenta::with('usuario')->get();
        } catch (\Exception $e) {
            return response()->json([
                'error'=> $e->getMessage()
                ],404);
        }
    }

    /**
     * Guarda una nueva cuenta en la base de datos
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|exists:usuarios,id',
                'nombreTitular_cuenta' => 'required|string|max:100',
                'numero_cuenta' => 'required|string|max:20',
                'nombreBanco_cuenta' => 'required|string|max:50',
                'nit_cuenta' => 'nullable|string|max:25',
                'ci_cuenta' => 'nullable|string|max:20',
                'tipo_cuenta' => 'required|string|max:15',
            ]);

            $cuenta = Cuenta::create($data);

            return response()->json($cuenta, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'menssage' => 'Error en la validacion',
                'error' => $e->validator->errors()
                ],422);
        } catch (\Exception $e) {
            return response()->json([
                'error'=> $e->getMessage()
                ],500);
        }
    }

    /**
     * Muestra una cuenta por Id
     */
    public function show(string $id)
    {
        try {
            $cuenta = Cuenta::with('usuario')->findOrFail($id);
        return response()->json($cuenta);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cuenta no encontrado'],404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }

    /**
     * Guarda los cambios de la cuenta editado en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        try {
            $cuenta = Cuenta::findOrFail($id);

            $data = $request->validate([
                'nombreTitular_cuenta' => 'sometimes|string|max:100',
                'numero_cuenta' => 'sometimes|string|max:20',
                'nombreBanco_cuenta' => 'sometimes|string|max:50',
                'nit_cuenta' => 'nullable|string|max:25',
                'ci_cuenta' => 'nullable|string|max:20',
                'tipo_cuenta' => 'sometimes|string|max:15',
            ]);

            $cuenta->update($data);

            return response()->json($cuenta);
        } catch(ModelNotFoundException $e){
            return response()->json([
                'menssage' => 'Cuenta no encontrado',
            ],404);
        }catch (ValidationException $e) {
            return response()->json([
                'menssage' => 'Error de validacion',
                'error'=> $e->validator->errors(),
            ],422);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * Elimina una cuenta especifico
     */
    public function destroy(string $id)
    {
        try {
            $cuenta = Cuenta::findOrFail($id);
            $cuenta->delete();

            return response()->json(['message' => 'Cuenta eliminada correctamente']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message'=> 'Cuenta no encontrado',
            ],404);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'Error inesperado',
                'error' => $e->getMessage(),
            ],422);
        }
    }

    /**
     * Devuelve la cuenta relacionada a usuario
     */
    public function getByUserId($user_id)
    {
        try {
            $cuenta = Cuenta::where('user_id',$user_id)->get();
            return response()->json(['cuentas'=> $cuenta]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado'],404);
        }
    }
}
