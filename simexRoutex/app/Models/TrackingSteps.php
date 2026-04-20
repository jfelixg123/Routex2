<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class TrackingSteps extends Model
{
    protected $table = 'tracking_steps';
    /**
     * Get all of the incoterms for the TrackingSteps
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incoterms(): HasMany
    {
        return $this->hasMany(Incoterm::class, 'tracking_steps_id');
    }
}
