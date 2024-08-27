<?php

namespace App\Observers;

use App\Models\Vessel;

class VesselObserver
{
    public function updating(Vessel $vessel): void
    {
        if ($vessel->isDirty('name')) {
            foreach ($vessel->voyages as $voyage) {
                $voyage->code = "{$vessel->name}-{$voyage->start->format('Y-m-d')}";
                $voyage->save();
            }
        }
    }
}
