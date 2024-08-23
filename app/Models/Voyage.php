<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_id',
        'code',
        'start',
        'end',
        'status',
        'revenues',
        'expenses',
        'profit',
    ];

    public function vessel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public static function boot(): void
    {
        static::creating(function ($voyage) {
            $voyage->code = $voyage->generateCode();
        });
    }

    public function generateCode(): string
    {
        return  $this->vessel->name . '-' . $this->start->format('Y-m-d');
    }
}
