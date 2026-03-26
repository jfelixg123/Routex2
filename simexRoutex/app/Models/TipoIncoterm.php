<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class TipoIncoterm extends Model
{
    protected $table = 'tipus_incoterms';
    public $timestamps = false;
    /**
     * Get all of the incoterms for the TipoIncotermController
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incoterms(): HasMany
    {
        return $this->hasMany(Incoterm::class, 'tipus_inconterm_id');
    }
}
