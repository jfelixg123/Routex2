<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransportistaResource;
use App\Models\Transportista;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TransportistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transportista = Transportista::with(['pais', 'aeroports', 'transportistas', 'liniasTransporteMaritimo', 'ports', 'ofertas'])->get();
        return TransportistaResource::collection($transportista);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transportista = new Transportista();
        $transportista->nom = $request->input('nom');
        $transportista->ciutat_id = $request->input('ciutat_id');

        try{
            $transportista->save();
            $response = (new TransportistaResource($transportista))
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
    public function show(Transportista $transportista)
    {
        $transportista = Transportista::with(['ofertas'])->find($transportista->id);
        return new TransportistaResource($transportista);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transportista = Transportista::find($id);
            if(!$transportista){
                $response = response()->json([
                    'error' => 'Transportista no trobat',
                ], 404);
            }else{
            $transportista->nom = $request->input('nom');
            $transportista->ciutat_id = $request->input('ciutat_id');

                try{
                    $transportista->save();
                    $response = (new TransportistaResource($transportista))
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
            $transportista = Transportista::find($id);
            if(!$transportista){
                $response = response()->json([
                    'error' => 'Transportista no trobat',
                ], 404);
            }else{
                $transportista->delete();
                $response = (new TransportistaResource($transportista))
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
