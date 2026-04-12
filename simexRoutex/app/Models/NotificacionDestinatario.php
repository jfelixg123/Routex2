<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacionDestinatario extends Model
{
    protected $table = 'notificacion_destinatarios';
    public $timestamps = false;

    public function usuario(): BelongsTo {
        return $this->belongsTo(Usuari::class, 'user_id');
    }

    public function empresa(): BelongsTo {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
