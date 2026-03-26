<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    protected $table = 'companies';
    public $timestamps = false;
    /**
     * Get all of the usuaris for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    //Para Buscar Usuarios que tiene una empresa

    public function usuaris(): HasMany
    {
        return $this->hasMany(Usuari::class, 'company_id');
    }

    /**
     * Get the user that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    //Para Buscar la Industria que tiene una empresa

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industria_id');
    }
}
