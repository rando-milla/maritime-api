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

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($voyage) {
            $voyage->code = $voyage->generateCode();

            $voyage->status = 'pending';
        });

        static::saving(function ($voyage) {
            // Update 'code' if 'start' date changes
            if ($voyage->isDirty('start')) {
                $voyage->code = $voyage->generateCode();
            }

            // Handle status changes to 'submitted'
            if ($voyage->isDirty('status') && $voyage->status === 'submitted') {
                // Ensure required fields are present
                if (is_null($voyage->start) || is_null($voyage->end) || is_null($voyage->revenues) || is_null($voyage->expenses)) {
                    throw new \Exception('Cannot submit voyage: start, end, revenues, and expenses must not be null.');
                }
                $voyage->profit = $voyage->revenues - $voyage->expenses;
            }
        });
    }
    protected $casts = [
        'start' => 'datetime:Y-m-d H:i:s',
        'end' => 'datetime:Y-m-d H:i:s',
        ];

    public function generateCode(): string
    {
        return  $this->vessel->name . '-' . $this->start->format('Y-m-d');
    }
}
