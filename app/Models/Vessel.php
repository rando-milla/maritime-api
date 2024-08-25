<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'imo_number'];

    public function voyages()
    {
        return $this->hasMany(Voyage::class);
    }

    public function opex()
    {
        return $this->hasMany(VesselOpex::class);
    }

    public static function boot()
    {
        parent::boot();

        static::updated(function ($vessel) {
            // Update voyage codes when vessel name is changed
            $vessel->voyages->each(function ($voyage) {
                $voyage->code = $voyage->generateCode();
                $voyage->save();
            });
        });
    }
}
