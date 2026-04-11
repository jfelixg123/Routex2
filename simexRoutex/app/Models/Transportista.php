<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transportista extends Model
{
    protected $table = 'transportistes';
    public $timestamps = false;

    /**
     * Get the ciutat that owns the Transportista
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    //Devuelve la ciudad en la que esta el transportista

    public function ciutat(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'ciutat_id');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'transportista_id');
    }

}
