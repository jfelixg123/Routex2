<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfertaSeguimiento extends Model
{
    protected $table = 'oferta_seguimiento';
    public $timestamps = false;

    public function step(): BelongsTo {
        return $this->belongsTo(TrackingSteps::class, 'tracking_step_id');
    }
}
