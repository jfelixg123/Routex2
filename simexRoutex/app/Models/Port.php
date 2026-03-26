<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Port extends Model
{
    protected $table = 'ports';
    public $timestamps = false;

    /**
     * Get the ciutat that owns the Port
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    //Devuelve la ciudad de un puerto

    public function ciutat(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'id_ciutat');
    }
    //Devuelve la ciudad de un puerto

}
