<?php

namespace App\Http\Controllers;

use App\Models\HorarioTienda;
use App\Models\Tienda;
use Illuminate\Http\Request;

class HorarioTiendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tienda_id)
    {
        $tienda = Tienda::with('horarios')->findOrFail($tienda_id);
        return response()->json($tienda->horarios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $tienda_id)
    {
        $validated = $request->validate([
            'dia_semana' => 'required|string|max:20',
            'hora_apertura' => 'nullable|date_format:H:i',
            'hora_cierre' => 'nullable|date_format:H:i',
            'cerrado' => 'boolean',
        ]);

        $validated['tienda_id'] = $tienda_id;

        $horario = HorarioTienda::create($validated);
        return response()->json(['message' => 'Horario creado correctamente', 'data' => $horario]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $horario = HorarioTienda::findOrFail($id);
        return response()->json($horario);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $horario = HorarioTienda::findOrFail($id);

        $validated = $request->validate([
            'dia_semana' => 'sometimes|string|max:20',
            'hora_apertura' => 'nullable|date_format:H:i',
            'hora_cierre' => 'nullable|date_format:H:i',
            'cerrado' => 'boolean',
        ]);

        $horario->update($validated);
        return response()->json(['message' => 'Horario actualizado correctamente', 'data' => $horario]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $horario = HorarioTienda::findOrFail($id);
        $horario->delete();
        return response()->json(['message' => 'Horario eliminado correctamente']);
    }
    /** Mostar producto por tienda */
    public function getHorarioTienda($tiendaId)
    {
        try {
            $horarios = HorarioTienda::where('tienda_id', $tiendaId)->get();
            if ($horarios->isEmpty()) {
                return response()->json(['message' => 'No se encontraron horarios para esta tienda.'], 404);
            }

            return response()->json($horarios);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error inesperado',
                'error'=> $e->getMessage(),
            ],500);
        }
    }
}
