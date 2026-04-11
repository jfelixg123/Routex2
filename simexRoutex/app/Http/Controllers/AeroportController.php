<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\AeroportsResource;
use App\Models\Aeroport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AeroportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aeroports = Aeroport::with(['ciutat', 'ofertasOrigen', 'ofertasDestino'])->get();
        return AeroportsResource::collection($aeroports);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $aeroport = new Aeroport();
        $aeroport->codi = $request->input('codi');
        $aeroport->nom = $request->input('nom');
        $aeroport->id_ciutat = $request->input('id_ciutat');

        try{
            $aeroport->save();
            $response = (new AeroportsResource($aeroport))
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
    public function show(Aeroport $aeroport)
    {
        $aeroport = Aeroport::with(['ciutat', 'ofertasOrigen', 'ofertasDestino'])->find($aeroport->id);
        return new AeroportsResource($aeroport);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $aeroport = Aeroport::find($id);
            if(!$aeroport){
                $response = response()->json([
                    'error' => 'Aeroport no trobat',
                ], 404);
            }else{
            $aeroport->codi = $request->input('codi');
            $aeroport->nom = $request->input('nom');
            $aeroport->id_ciutat = $request->input('id_ciutat');

                try{
                    $aeroport->save();
                    $response = (new AeroportsResource($aeroport))
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
            $aeroport = Aeroport::find($id);
            if(!$aeroport){
                $response = response()->json([
                    'error' => 'Aeroport no trobat',
                ], 404);
            }else{
                $aeroport->delete();
                $response = (new AeroportsResource($aeroport))
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
