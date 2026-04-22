<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    //

    public function incoterm(): BelongsTo
    {
        return $this->belongsTo(Incoterm::class, 'incoterm_id');
    }

    public function transportista(): BelongsTo
    {
        return $this->belongsTo(Transportista::class, 'transportista_id');
    }

    public function portOrigen(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'port_origen_id');
    }

    public function portDesti(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'port_desti_id');
    }

    public function linia(): BelongsTo
    {
        return $this->belongsTo(LineTransMar::class, 'linia_transport_maritim_id');
    }

    public function aeroportOrigen(): BelongsTo
    {
        return $this->belongsTo(Aeroport::class, 'aeroport_origen_id');
    }

    public function aeroportDesti(): BelongsTo
    {
        return $this->belongsTo(Aeroport::class, 'aeroport_desti_id');
    }

    public function divisa(): BelongsTo
    {
        return $this->belongsTo(Divisas::class, 'divisas_id');
    }

    public function usuari(): BelongsTo
    {
        // Usamos Usuari::class y la columna client_id que sale en tu JSON
        return $this->belongsTo(Usuari::class, 'client_id');
    }

    // Relación para el Vendedor (Sales Rep)
    public function agentComercial(): BelongsTo
    {
        // Usamos Usuari::class y la columna agent_comercial_id
        return $this->belongsTo(Usuari::class, 'agent_comercial_id');
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(OfertaSeguimiento::class, 'oferta_id');
    }
}
