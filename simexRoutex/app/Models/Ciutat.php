<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Ciutat extends Model
{
    protected $table = 'ciutats';
    public $timestamps = false;

    /**
     * Get all of the pais for the Ciutat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pais(): BelongsTo
    {
        return $this->belongsTo(Paissos::class, 'pais_id');
    }

    /**
     * Get all of the aeroports for the Ciutat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    //Devuelve los aeropuertos de una ciudad
    public function aeroports(): HasMany
    {
        return $this->hasMany(Aeroport::class, 'id_ciutat');
    }

    /**
     * Get all of the transportistas for the Ciutat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    //Devuelve los transportistas de esa ciudad

    public function transportistas(): HasMany
    {
        return $this->hasMany(Transportista::class, 'ciutat_id');
    }

    /**
     * Get all of the liniasTransporteMaritimo for the Ciutat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function liniasTransporteMaritimo(): HasMany
    {
        return $this->hasMany(LineTransMar::class, 'ciutat_id');
    }

    /**
     * Get all of the ports for the Ciutat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    //Devuelve los puertos de una ciudad

    public function ports(): HasMany
    {
        return $this->hasMany(Port::class, 'id_ciutat');
    }
}
