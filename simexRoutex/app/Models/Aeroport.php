<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aeroport extends Model
{
    protected $table = 'aeroports';
    public $timestamps = false;

    /**
     * Get the ciutats that owns the Aeroport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    //Devuelve la ciudad de un aeropuerto
    public function ciutat(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'id_ciutat');
    }

    public function ofertasOrigen(): HasMany
    {
        return $this->hasMany(Oferta::class, 'aeroport_origen_id');
    }

    public function ofertasDestino(): HasMany
    {
        return $this->hasMany(Oferta::class, 'aeroport_desti_id');
    }
}
