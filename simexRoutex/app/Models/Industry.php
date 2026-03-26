<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Industry extends Model
{
    protected $table = 'industria';
    public $timestamps = false;
    /**
     * Get all of the companies for the Industry
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // Para buscar las empresas de una industria

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'industria_id');
    }
}
