<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incoterm extends Model
{
    protected $table = 'incoterms';
    public $timestamps = false;

    /**
     * Get the trackingSteps that owns the Incoterm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function trackingSteps(): BelongsTo
    {
        return $this->belongsTo(TrackingSteps::class, 'tracking_steps_id');
    }

    /**
     * Get the tiposIncoterm that owns the Incoterm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tiposIncoterm(): BelongsTo
    {
        return $this->belongsTo(TipoIncoterm::class, 'tipus_inconterm_id');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'incoterm_id');
    }
}
