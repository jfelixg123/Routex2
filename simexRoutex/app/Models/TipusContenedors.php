<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class TipusContenedors extends Model
{
    protected $table = 'tipus_contenidors';
    public $timestamps = false;

    /**
     * Get all of the ofertas for the TipusContenidors
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'tipus_contenidor_id');
    }
}
