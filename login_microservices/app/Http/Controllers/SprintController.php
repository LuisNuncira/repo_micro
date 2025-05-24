<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function index()
    {
        $sprints = Sprint::orderBy('fecha_inicio', 'desc')->get();
        return response()->json($sprints);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $sprint = Sprint::create($request->all());
        return response()->json($sprint, 201);
    }

    public function show(Sprint $sprint)
    {
        $sprint->load('retroItems');
        return response()->json($sprint);
    }

    public function update(Request $request, Sprint $sprint)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $sprint->update($request->all());
        return response()->json($sprint);
    }

    public function destroy(Sprint $sprint)
    {
        $sprint->delete();
        return response()->json(['message' => 'Sprint eliminado exitosamente']);
    }
}