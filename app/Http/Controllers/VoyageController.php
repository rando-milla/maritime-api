<?php
namespace App\Http\Controllers;

use App\Http\Requests\Voyage\CreateVoyageRequest;
use App\Http\Requests\Voyage\UpdateVoyageRequest;
use App\Models\Voyage;
use App\Services\VoyageService;

class VoyageController extends Controller
{
    protected $voyageService;

    public function __construct(VoyageService $voyageService)
    {
        $this->voyageService = $voyageService;
    }

    /**
     * @throws \Exception
     */
    public function store(CreateVoyageRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $voyage = $this->voyageService->createVoyage($request->validated());
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }

        return response()->json($voyage, 201);

    }

    /**
     * Update an existing voyage.
     * @throws \Exception
     */
    public function update(UpdateVoyageRequest  $request, Voyage $voyage)
    {
        try {
            $updatedVoyage = $this->voyageService->updateVoyage($voyage, $request->validated());
        } catch (\Error $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
        return response()->json($updatedVoyage);

    }
}
