<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncotermPaso extends Model
{
    protected $table = 'incoterm_pasos';
    public $timestamps = false;

    public function trackingStep(): BelongsTo
    {
        return $this->belongsTo(TrackingSteps::class, 'tracking_step_id');
    }
}
