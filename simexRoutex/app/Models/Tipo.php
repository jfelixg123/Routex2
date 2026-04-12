<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Tipo extends Model
{
    protected $table = 'tipo';
    public $timestamps = false;

    /**
     * Get all of the incoterms for the TipoIncotermController
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notificaciones(): HasMany {
        return $this->hasMany(Notificacion::class, 'tipo_id');
    }
}
