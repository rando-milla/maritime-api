<?php

namespace App\Observers;

use App\Models\Vessel;
use App\Models\Voyage;

class VesselObserver
{
    public function updating(Vessel $vessel)
    {
        if ($vessel->isDirty('name')) {
            foreach ($vessel->voyages as $voyage) {
                $voyage->code = "{$vessel->name}-{$voyage->start->format('Y-m-d')}";
                $voyage->save();
            }
        }
    }
}
