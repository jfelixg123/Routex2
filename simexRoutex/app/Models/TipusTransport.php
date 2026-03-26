<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class TipusTransport extends Model
{
    protected $table = 'tipus_transports';
    public $timestamps = false;

    /**
     * Get all of the ofertas for the TipusTransport
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'tipus_transport_id');
    }
}
