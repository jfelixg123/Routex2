<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class EstatsOfertes extends Model
{
    protected $table = 'estats_ofertes';
    public $timestamps = false;

    /**
     * Get all of the ofertas for the EstatsOfertes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'estat_oferta_id');
    }
}
