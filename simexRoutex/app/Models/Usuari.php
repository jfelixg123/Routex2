<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuari extends Authenticatable
{
    use HasApiTokens;
    protected $connection = 'sqlsrv';
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
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Ofertas donde este usuario es el CLIENTE
     */
    public function ofertesComClient(): HasMany
    {
        // El segundo parámetro es la clave foránea en la tabla 'ofertes'
        return $this->hasMany(Oferta::class, 'client_id');
    }

    /**
     * Ofertas donde este usuario es el VENDEDOR (Agent Comercial)
     */
    public function ofertesComComercial(): HasMany
    {
        return $this->hasMany(Oferta::class, 'agent_comercial_id');
    }
}
