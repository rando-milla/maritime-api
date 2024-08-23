<?php
namespace App\Http\Controllers;

use App\Models\Voyage;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoyageController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'vessel_id' => 'required|exists:vessels,id',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'revenues' => 'nullable|numeric|min:0',
            'expenses' => 'nullable|numeric|min:0',
        ]);

        $vessel = Vessel::findOrFail($request->vessel_id);

        // Check if vessel has an ongoing voyage
        if ($vessel->voyages()->where('status', 'ongoing')->exists()) {
            return response()->json(['error' => 'This vessel has an ongoing voyage.'], 400);
        }

        $voyage = new Voyage($request->all());
        $vessel->voyages()->save($voyage);

        return response()->json($voyage, 201);
    }

    public function update(Request $request, Voyage $voyage): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after:start',
            'revenues' => 'nullable|numeric|min:0',
            'expenses' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(['pending', 'ongoing', 'submitted'])],
        ]);

        if ($voyage->status === 'submitted') {
            return response()->json(['error' => 'Cannot edit a submitted voyage.'], 400);
        }

        $voyage->fill($request->all());

        if ($voyage->status === 'submitted') {
            $voyage->profit = $voyage->revenues - $voyage->expenses;
        }

        $voyage->save();

        return response()->json($voyage, 200);
    }
}
