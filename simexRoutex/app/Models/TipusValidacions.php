<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class TipusValidacions extends Model
{
    protected $table = 'tipus_validacions';
    public $timestamps = false;

    /**
     * Get all of the ofertas for the TipusValidacions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'tipus_validacio_id');
    }
}
