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

    /**
     * Update an existing voyage.
     */
    public function update(Request $request, Voyage $voyage)
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

        // Handle status change to 'ongoing'
        if ($request->has('status') && $request->status === 'ongoing') {
            $vessel = $voyage->vessel;
            if ($vessel->voyages()->where('status', 'ongoing')->where('id', '!=', $voyage->id)->exists()) {
                return response()->json(['error' => 'This vessel already has an ongoing voyage.'], 400);
            }
        }

        try {
            $voyage->fill($request->all());
            $voyage->save();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($voyage, 200);
    }
}
