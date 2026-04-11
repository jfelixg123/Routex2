<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfertaResource;
use App\Models\Oferta;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ofertas = Oferta::with(['estatsOfertes', 'tipusValidacions', 'tipusContenidors', 'tipusCarrega', 'tipusTransport', 'tipusFluxe', 'incoterm', 'transportista', 'portOrigen', 'portDesti', 'linia', 'aeroportOrigen', 'aeroportDesti'])->get();
        return OfertaResource::collection($ofertas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $oferta = new Oferta();
        $oferta->tipus_transport_id = $request->input('tipus_transport_id');
        $oferta->tipus_fluxe_id = $request->input('tipus_fluxe_id');
        $oferta->tipus_carrega_id = $request->input('tipus_carrega_id');
        $oferta->incoterm_id = $request->input('incoterm_id');
        $oferta->client_id = $request->input('client_id');
        $oferta->comentaris = $request->input('comentaris');
        $oferta->agent_comercial_id = $request->input('agent_comercial_id');
        $oferta->transportista_id = $request->input('transportista_id');
        $oferta->pes_brut = $request->input('pes_brut');
        $oferta->volum = $request->input('volum');
        $oferta->tipus_validacio_id = $request->input('tipus_validacio_id');
        $oferta->port_origen_id = $request->input('port_origen_id');
        $oferta->port_desti_id = $request->input('port_desti_id');
        $oferta->aeroport_origen_id = $request->input('aeroport_origen_id');
        $oferta->aeroport_desti_id = $request->input('aeroport_desti_id');
        $oferta->linia_transport_maritim_id = $request->input('linia_transport_maritim_id');
        $oferta->estat_oferta_id = $request->input('estat_oferta_id');
        $oferta->operador_id = $request->input('operador_id');
        $oferta->data_creacio = $request->input('data_creacio');
        $oferta->data_validessa_inicial = $request->input('data_validessa_inicial');
        $oferta->data_validessa_fina = $request->input('data_validessa_fina');
        $oferta->rao_rebuig = $request->input('rao_rebuig');
        $oferta->tipus_contenidor_id = $request->input('tipus_contenidor_id');

        try{
            $oferta->save();
            $response = (new OfertaResource($oferta))
            ->response()
            ->setStatusCode(201);
        }catch(QueryException $e){
            $missatge = Utilitat::errorMessage($e);
            $response = response()->json([
                'error' =>  $missatge
            ], 400);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Oferta $oferta)
    {
        $oferta = Oferta::with(['estatsOfertes', 'tipusValidacions', 'tipusContenidors', 'tipusCarrega', 'tipusTransport', 'tipusFluxe', 'incoterm', 'transportista', 'portOrigen', 'portDesti', 'linia', 'aeroportOrigen', 'aeroportDesti'])->find($oferta->id);
        return new OfertaResource($oferta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $oferta = Oferta::find($id);
            if(!$oferta){
                $response = response()->json([
                    'error' => 'Tipu de oferta no trobada',
                ], 404);
            }else{
                $oferta->tipus_transport_id = $request->input('tipus_transport_id');
                $oferta->tipus_fluxe_id = $request->input('tipus_fluxe_id');
                $oferta->tipus_carrega_id = $request->input('tipus_carrega_id');
                $oferta->incoterm_id = $request->input('incoterm_id');
                $oferta->client_id = $request->input('client_id');
                $oferta->comentaris = $request->input('comentaris');
                $oferta->agent_comercial_id = $request->input('agent_comercial_id');
                $oferta->transportista_id = $request->input('transportista_id');
                $oferta->pes_brut = $request->input('pes_brut');
                $oferta->volum = $request->input('volum');
                $oferta->tipus_validacio_id = $request->input('tipus_validacio_id');
                $oferta->port_origen_id = $request->input('port_origen_id');
                $oferta->port_desti_id = $request->input('port_desti_id');
                $oferta->aeroport_origen_id = $request->input('aeroport_origen_id');
                $oferta->aeroport_desti_id = $request->input('aeroport_desti_id');
                $oferta->linia_transport_maritim_id = $request->input('linia_transport_maritim_id');
                $oferta->estat_oferta_id = $request->input('estat_oferta_id');
                $oferta->operador_id = $request->input('operador_id');
                $oferta->data_creacio = $request->input('data_creacio');
                $oferta->data_validessa_inicial = $request->input('data_validessa_inicial');
                $oferta->data_validessa_fina = $request->input('data_validessa_fina');
                $oferta->rao_rebuig = $request->input('rao_rebuig');
                $oferta->tipus_contenidor_id = $request->input('tipus_contenidor_id');

                try{
                    $oferta->save();
                    $response = (new OfertaResource($oferta))
                    ->response()
                    ->setStatusCode(201);
                }catch(QueryException $e){
                    $missatge = Utilitat::errorMessage($e);
                    $response = response()->json([
                        'error' =>  $missatge
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
        try{
            $oferta = Oferta::find($id);
            if(!$oferta){
                $response = response()->json([
                    'error' => 'Tipu de oferta no trobada',
                ], 404);
            }else{
                $oferta->delete();
                $response = (new OfertaResource($oferta))
                ->response()
                ->setStatusCode(200);
            }
        }catch(QueryException $e){
            $missatge = Utilitat::errorMessage($e);
            $response = response()->json([
                'error' =>  $missatge
            ], 400);
        }

        return $response;
    }
}
