<?php
namespace App\Http\Controllers;

use App\Models\Vessel;
use App\Models\VesselOpex;
use Illuminate\Http\Request;

class VesselOpexController extends Controller
{
    public function store(Request $request, $vesselId): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
            'expenses' => 'required|numeric|min:0',
        ]);

        $vessel = Vessel::findOrFail($vesselId);

        if ($vessel->opex()->where('date', $request->date)->exists()) {
            return response()->json(['error' => 'Opex for this date already exists.'], 400);
        }

        $opex = new VesselOpex($request->all());
        $vessel->opex()->save($opex);

        return response()->json($opex, 201);
    }
}
