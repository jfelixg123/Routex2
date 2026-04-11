<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usuari extends Model
{
    protected $table = 'usuaris';
    public $timestamps = false;

     /**
     * The roles that belong to the Usuari
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

     //Para Buscar Compañia que tiene un usuario

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'company_id');
    }
}
