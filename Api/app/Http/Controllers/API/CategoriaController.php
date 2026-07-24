<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $categorias = $request->user()->categorias()->orderBy('nombre')->get();
        return response()->json($categorias);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'color' => 'required|string|max:20', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categoria = $request->user()->categorias()->create($validator->validated());

        return response()->json($categoria, 201);
    }

    public function show(Request $request, Categoria $categoria)
    {
        if ($request->user()->id !== $categoria->usuario_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        return response()->json($categoria);
    }
    public function update(Request $request, Categoria $categoria)
    {
        if ($request->user()->id !== $categoria->usuario_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categoria->update($validator->validated());
        return response()->json($categoria);
    }

    public function destroy(Request $request, Categoria $categoria)
    {
        if ($request->user()->id !== $categoria->usuario_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $categoria->delete();
        return response()->json(null, 204);
    }
}