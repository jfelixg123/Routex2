<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\TipusTransportResource;
use App\Models\TipusTransport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipusTransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipusTransport = TipusTransport::with(['ofertas'])->get();
        return TipusTransportResource::collection($tipusTransport);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipusTransport = new TipusTransport();
        $tipusTransport->tipus = $request->input('tipus');

        try{
            $tipusTransport->save();
            $response = (new TipusTransportResource($tipusTransport))
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
    public function show(TipusTransport $tipusTransport)
    {
        $tipusTransport = TipusTransport::with(['ofertas'])->find($tipusTransport->id);
        return new TipusTransportResource($tipusTransport);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipusTransport = TipusTransport::find($id);
            if(!$tipusTransport){
                $response = response()->json([
                    'error' => 'Tipu de transport no trobat',
                ], 404);
            }else{
                $tipusTransport->tipus = $request->input('tipus');

                try{
                    $tipusTransport->save();
                    $response = (new TipusTransportResource($tipusTransport))
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
            $tipusTransport = TipusTransport::find($id);
            if(!$tipusTransport){
                $response = response()->json([
                    'error' => 'Tipu de transport no trobat',
                ], 404);
            }else{
                $tipusTransport->delete();
                $response = (new TipusTransportResource($tipusTransport))
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
