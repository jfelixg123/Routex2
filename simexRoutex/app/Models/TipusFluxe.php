<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class TipusFluxe extends Model
{
    protected $table = 'tipus_fluxes';
    public $timestamps = false;

    /**
     * Get all of the ofertas for the TipusFluxe
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'tipus_fluxe_id');
    }
}
