<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    /**
     * Get all of the usuaris for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usuaris(): HasMany
    {
        return $this->hasMany(Usuari::class, 'company_id');
    }
}
