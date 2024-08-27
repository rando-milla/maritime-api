<?php
namespace App\Http\Controllers;

use App\Http\Requests\VesselOpex\CreateVesselOpexRequest;
use App\Http\Requests\VesselOpex\UpdateVesselOpexRequest;
use App\Models\Vessel;
use App\Models\VesselOpex;

class VesselOpexController extends Controller
{
    public function store(CreateVesselOpexRequest $request, $vesselId): \Illuminate\Http\JsonResponse
    {
        $request->validated();

        $vessel = Vessel::findOrFail($vesselId);

        if ($vessel->opex()->where('date', $request->date)->exists()) {
            return response()->json(['error' => 'Opex for this date already exists.'], 400);
        }

        $opex = new VesselOpex($request->all());
        $vessel->opex()->save($opex);

        return response()->json($opex, 201);
    }

    public function update(UpdateVesselOpexRequest $request, VesselOpex $vesselOpex)
    {
        $vesselOpex->update($request->validated());
        return response()->json($vesselOpex);
    }
}
