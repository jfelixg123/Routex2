<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Oferta extends Model
{
    protected $table = 'ofertes';
    public $timestamps = false;

    /**
     * Get the estatsOfertes that owns the OfertaController
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estatsOfertes(): BelongsTo
    {
        return $this->belongsTo(EstatsOfertes::class, 'estat_oferta_id');
    }

    /**
     * Get the tipusValidacions that owns the OfertaController
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipusValidacions(): BelongsTo
    {
        return $this->belongsTo(TipusValidacions::class, 'tipus_validacio_id');
    }

    /**
     * Get the tipusContenidors that owns the OfertaController
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipusContenidors(): BelongsTo
    {
        return $this->belongsTo(TipusContenedors::class, 'tipus_contenidor_id');
    }

    /**
     * Get the tipusCarrega that owns the OfertaController
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipusCarrega(): BelongsTo
    {
        return $this->belongsTo(TipusCarrega::class, 'tipus_carrega_id');
    }

    /**
     * Get the tipusTransport that owns the OfertaController
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipusTransport(): BelongsTo
    {
        return $this->belongsTo(TipusTransport::class, 'tipus_transport_id');
    }

    /**
     * Get the tipusFluxe that owns the OfertaController
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipusFluxe(): BelongsTo
    {
        return $this->belongsTo(TipusFluxe::class, 'tipus_fluxe_id');
    }
    // sd
}
