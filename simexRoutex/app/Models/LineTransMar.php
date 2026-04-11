<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LineTransMar extends Model
{
    protected $table = 'linies_transport_maritim';
    public $timestamps = false;
    /**
     * Get the ciutat that owns the LineTransMar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    //Devuelve la ciudad de una linea maritima

    public function ciutat(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'ciutat_id');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'linia_transport_maritim_id');
    }
}
