<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisas extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'divisas';
    public $timestamps = false;

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'divisas_id');
    }
}
