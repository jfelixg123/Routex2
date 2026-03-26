<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Paissos extends Model
{
    protected $table = 'paissos';
    public $timestamps = false;

    /**
     * Get the ciutats that owns the PaissosController
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    //Devuelve las ciudades de un pais

    public function ciutats(): HasMany
    {
        return $this->hasMany(Ciutat::class, 'pais_id');
    }
}
