<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    public $timestamps = false; // Según tu esquema no veo timestamps

    public function tipo(): BelongsTo {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }

    public function destinatarios(): HasMany {
        return $this->hasMany(NotificacionDestinatario::class, 'notificacion_id');
    }
}
