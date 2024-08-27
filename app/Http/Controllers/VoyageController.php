<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateVoyageRequest;
use App\Http\Requests\UpdateVoyageRequest;
use App\Models\Voyage;
use App\Models\Vessel;
use App\Services\VoyageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
