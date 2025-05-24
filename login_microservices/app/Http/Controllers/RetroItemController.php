<?php

namespace App\Http\Controllers;

use App\Models\RetroItem;
use App\Models\Sprint;
use Illuminate\Http\Request;

class RetroItemController extends Controller
{
    public function index()
    {
        $items = RetroItem::with('sprint')->orderBy('created_at', 'desc')->get();
        return response()->json($items);
    }

    public function show(RetroItem $retroItem)
    {
        $retroItem->load('sprint');
        return response()->json($retroItem);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sprint_id' => 'required|exists:sprints,id',
            'categoria' => 'required|in:accion,logro,impedimento,comentario,otro',
            'descripcion' => 'required',
            'fecha_revision' => 'nullable|date'
        ]);

        $retroItem = RetroItem::create($request->all());
        $retroItem->load('sprint');
        return response()->json($retroItem, 201);
    }

    public function update(Request $request, RetroItem $retroItem)
    {
        $request->validate([
            'descripcion' => 'required',
            'cumplida' => 'nullable|boolean',
            'fecha_revision' => 'nullable|date'
        ]);

        $retroItem->update($request->all());
        $retroItem->load('sprint');
        return response()->json($retroItem);
    }

    public function destroy(RetroItem $retroItem)
    {
        $retroItem->delete();
        return response()->json(['message' => 'Item eliminado exitosamente']);
    }

    // Métodos específicos adicionales
    public function getBySprintId(Sprint $sprint)
    {
        $items = $sprint->retroItems()->orderBy('categoria')->get();
        return response()->json($items);
    }

    public function toggleCumplida(RetroItem $retroItem)
    {
        $retroItem->update(['cumplida' => !$retroItem->cumplida]);
        return response()->json($retroItem);
    }

    public function getAcciones(Sprint $sprint)
    {
        $acciones = $sprint->retroItems()
            ->where('categoria', 'accion')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($acciones);
    }

    public function getLogros(Sprint $sprint)
    {
        $logros = $sprint->retroItems()
            ->where('categoria', 'logro')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($logros);
    }

    public function getImpedimentos(Sprint $sprint)
    {
        $impedimentos = $sprint->retroItems()
            ->where('categoria', 'impedimento')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($impedimentos);
    }
}