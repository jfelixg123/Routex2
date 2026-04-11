<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Port extends Model
{
    protected $table = 'ports';
    public $timestamps = false;

    /**
     * Get the ciutat that owns the Port
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    //Devuelve la ciudad de un puerto

    public function ciutat(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'id_ciutat');
    }
    //Devuelve la ciudad de un puerto

    public function ofertasOrigen(): HasMany
    {
        return $this->hasMany(Oferta::class, 'port_origen_id');
    }

    public function ofertasDestino(): HasMany
    {
        return $this->hasMany(Oferta::class, 'port_desti_id');
    }

}
