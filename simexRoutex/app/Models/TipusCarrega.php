<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class TipusCarrega extends Model
{
    protected $table = 'tipus_carrega';
    public $timestamps = false;

    /**
     * Get all of the ofertas for the TipusCarrega
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'tipus_carrega_id');
    }
}
