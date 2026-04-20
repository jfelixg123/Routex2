<?php

namespace App\Http\Controllers;

use App\Classes\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfertaResource;
use App\Models\Incoterm;
use App\Models\Oferta;
use App\Models\OfertaSeguimiento;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use function Symfony\Component\Clock\now;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $ofertas = Oferta::with(['estatsOfertes', 'tipusValidacions', 'tipusContenidors', 'tipusCarrega', 'tipusTransport', 'tipusFluxe', 'incoterm', 'transportista', 'portOrigen', 'portDesti', 'linia', 'aeroportOrigen', 'aeroportDesti', 'divisa', 'usuari', 'agentComercial'])->get();
        return OfertaResource::collection($ofertas);
    }

    public function historial()
    {
        $ofertas = Oferta::with(['estatsOfertes', 'tipusValidacions', 'tipusContenidors', 'tipusCarrega', 'tipusTransport', 'tipusFluxe', 'incoterm', 'transportista', 'portOrigen', 'portDesti', 'linia', 'aeroportOrigen', 'aeroportDesti', 'divisa', 'usuari', 'agentComercial'])
            ->where('client_id', auth()->id())
            ->get();

        return OfertaResource::collection($ofertas);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'concepto' => 'required',
        ]);

        $oferta = new Oferta();
        $oferta->tipus_transport_id = $request->transporte_id;
        $oferta->tipus_fluxe_id = $request->flujos_id;
        $oferta->tipus_carrega_id = $request->cargas_id;
        $oferta->incoterm_id = $request->incoterm_id;
        if (auth()->user()->rol === 1) {
            $oferta->client_id = $request->cliente_id;
        } else {
            $oferta->client_id = auth()->id();
        }
        $oferta->descrip_mercancia = $request->descripcion;
        $oferta->agent_comercial_id = $request->vendedor_id;
        $oferta->transportista_id = $request->transporte_id;
        $oferta->pes_brut = $request->peso;
        $oferta->volum = $request->volumen;
        $oferta->tipus_validacio_id = 1;
        $oferta->port_origen_id = $request->puerto_id;
        $oferta->port_desti_id = $request->puerto_destino_id;
        $oferta->aeroport_origen_id = $request->aeropuerto_id;
        $oferta->aeroport_desti_id = $request->aeropuerto_destino_id;
        $oferta->linia_transport_maritim_id = $request->lineaTransporte_id;
        $oferta->estat_oferta_id = $request->estat_oferta_id ?? 1;
        $oferta->operador_id = 1;
        $oferta->data_creacio = $request->data_creacio ?? now();
        $oferta->data_validessa_inicial = $request->valid_desde;
        $oferta->data_validessa_fina = $request->valid_fins;
        $oferta->rao_rebuig = "";
        $oferta->tipus_contenidor_id = 1;
        $oferta->divisas_id = $request->divisas_id;
        $oferta->concepto = $request->concepto;
        $oferta->bultos = $request->bultos;
        $oferta->valor = $request->valor;
        $oferta->comentarios_internos = $request->comentarios_internos;
        $oferta->comentarios_imprimir = $request->comentarios_imprimir;

        try {
            $oferta->save();

            $incoterm = Incoterm::with('pasosAsociados')->find($oferta->incoterm_id);

            if ($incoterm && $incoterm->pasosAsociados) {
                foreach ($incoterm->pasosAsociados as $item) {
                    $seguimiento = new OfertaSeguimiento();
                    $seguimiento->oferta_id = $oferta->id;
                    $seguimiento->tracking_step_id = $item->tracking_step_id;
                    $seguimiento->orden = $item->orden;
                    $seguimiento->esta_completado = 0;
                    $seguimiento->save();
                }
            }

            $response = (new OfertaResource($oferta))
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            $missatge = Utilitat::errorMessage($e);
            $response = response()->json([
                'error' => $missatge
            ], 400);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Oferta $oferta)
    {
        $oferta = Oferta::with(['estatsOfertes', 'tipusValidacions', 'tipusContenidors', 'tipusCarrega', 'tipusTransport', 'tipusFluxe', 'incoterm', 'transportista', 'portOrigen', 'portDesti', 'linia', 'aeroportOrigen', 'aeroportDesti', 'divisa', 'usuari', 'agentComercial'])->find($oferta->id);
        return new OfertaResource($oferta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $oferta = Oferta::find($id);
        if (!$oferta) {
            $response = response()->json([
                'error' => 'Tipu de oferta no trobada',
            ], 404);
        } else {
            $oferta->tipus_transport_id = $request->transporte_id;
            $oferta->tipus_fluxe_id = $request->flujos_id;
            $oferta->tipus_carrega_id = $request->cargas_id;
            $oferta->incoterm_id = $request->incoterm_id;
            $oferta->client_id = $request->cliente_id;
            $oferta->descrip_mercancia = $request->descripcion;
            $oferta->agent_comercial_id = $request->vendedor_id;
            $oferta->transportista_id = $request->transportista_id; // Corregido: antes tenías transporte_id aquí
            $oferta->pes_brut = $request->peso;
            $oferta->volum = $request->volumen;
            $oferta->port_origen_id = $request->puerto_id;
            $oferta->port_desti_id = $request->puerto_destino_id;
            $oferta->aeroport_origen_id = $request->aeropuerto_id;
            $oferta->aeroport_desti_id = $request->aeropuerto_destino_id;
            $oferta->linia_transport_maritim_id = $request->lineaTransporte_id;
            $oferta->estat_oferta_id = $request->estat_oferta_id;
            $oferta->data_creacio = $request->data_creacio;
            $oferta->data_validessa_inicial = $request->valid_desde;
            $oferta->data_validessa_fina = $request->valid_fins;
            $oferta->divisas_id = $request->divisas_id;
            $oferta->concepto = $request->concepto;
            $oferta->bultos = $request->bultos;
            $oferta->valor = $request->valor;
            $oferta->comentarios_internos = $request->comentarios_internos;
            $oferta->comentarios_imprimir = $request->comentarios_imprimir;

            try {
                $oferta->save();
                $response = (new OfertaResource($oferta))
                    ->response()
                    ->setStatusCode(201);
            } catch (QueryException $e) {
                return response()->json([
                    'error' => 'Error de BD: ' . $e->getMessage()
                ], 400);
            }
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $oferta = Oferta::find($id);
            if (!$oferta) {
                $response = response()->json([
                    'error' => 'Tipu de oferta no trobada',
                ], 404);
            } else {
                $oferta->delete();
                $response = (new OfertaResource($oferta))
                    ->response()
                    ->setStatusCode(200);
            }
        } catch (QueryException $e) {
            $missatge = Utilitat::errorMessage($e);
            $response = response()->json([
                'error' => $missatge
            ], 400);
        }

        return $response;
    }
}
