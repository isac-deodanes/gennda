<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mes' => 'required|integer|min:1|max:12',
            'ano' => 'required|integer|min:2000|max:2100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $eventos = $request->user()->eventos()
            ->whereYear('fecha', $request->ano)
            ->whereMonth('fecha', $request->mes)
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get();
        return response()->json($eventos);
    }
public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date_format:Y-m-d',
            'hora' => 'nullable|date_format:H:i',
            'descripcion' => 'nullable|string',
            'gasto_estimado' => 'nullable|numeric|min:0|max:99999999.99',
            'categoria_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $datosValidados = $validator->validated();

        if ($request->has('categoria_id') && $request->categoria_id) {
            $categoria = $request->user()->categorias()->find($request->categoria_id);
            if (!$categoria) {
                return response()->json(['message' => 'La categoría seleccionada no es válida.'], 422);
            }
            $datosValidados['categoria_id'] = $categoria->id;
        }

        $evento = $request->user()->eventos()->create($datosValidados);
        $evento->load('categoria'); 
        return response()->json($evento, 201);
    }
    public function show(Request $request, Evento $evento)
    {
        if ($request->user()->id !== $evento->usuario_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($evento);
    }
    public function update(Request $request, Evento $evento)
    {
        if ($request->user()->id !== $evento->usuario_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|required|string|max:255',
            'fecha' => 'sometimes|required|date_format:Y-m-d',
            'hora' => 'nullable|date_format:H:i',
            'descripcion' => 'nullable|string',
            'gasto_estimado' => 'nullable|numeric|min:0|max:99999999.99',
            'categoria_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $datosValidados = $validator->validated();
        if ($request->has('categoria_id') && $request->categoria_id) {
            $categoria = $request->user()->categorias()->find($request->categoria_id);
            if (!$categoria) {
                return response()->json(['message' => 'La categoría seleccionada no es válida.'], 422);
            }
            $datosValidados['categoria_id'] = $categoria->id;
        }

        $evento->update($datosValidados);
        return response()->json($evento->load('categoria'));
    }
    public function destroy(Request $request, Evento $evento)
    {
        if ($request->user()->id !== $evento->usuario_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $evento->delete();
        return response()->json(null, 204);
    }
}