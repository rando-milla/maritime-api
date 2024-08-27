<?php
namespace App\Services;

use App\Models\Voyage;
use App\Models\Vessel;
use App\Repositories\VoyageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoyageService
{
    protected $voyageRepository;

    public function __construct(VoyageRepository $voyageRepository)
    {
        $this->voyageRepository = $voyageRepository;
    }

    /**
     * @throws \Exception
     */
    public function createVoyage(array $data): Voyage
    {
        $vessel = Vessel::findOrFail($data['vessel_id']);

        // Check if vessel has an ongoing voyage
        if ($vessel->voyages()->where('status', 'ongoing')->exists()) {
            throw new \Error('This vessel has an ongoing voyage.', 400);
        }

        $voyage = new Voyage([
            'vessel_id' => $vessel->id,
            'start' => $data['start'],
            'end' => $data['end'] ?? null,
            'revenues' => $data['revenues'] ?? 0,
            'expenses' => $data['expenses'] ?? 0,
            'status' => 'pending',
        ]);
        $voyage->code = $this->generateVoyageCode($vessel->name, $voyage->start);

        $vessel->voyages()->save($voyage);
        return $voyage;
    }

    /**
     * @throws \Exception
     */
    public function updateVoyage(Voyage $voyage, array $data): Voyage
    {
        if ($voyage->status === 'submitted') {
            throw new \Error('Cannot edit a submitted voyage.', 400);
        }

        $voyage->fill($data);

        if ($voyage->isDirty('start')) {
            $voyage->code = $this->generateVoyageCode($voyage->vessel->name, $voyage->start);
        }

        if ($voyage->status === 'submitted') {
            $voyage->profit = $voyage->revenues - $voyage->expenses;
        }

        $voyage->save();

        return $voyage;
    }

    protected function generateVoyageCode(string $vesselName, $start): string
    {
        return "{$vesselName}-{$start->format('Y-m-d')}";
    }
}
