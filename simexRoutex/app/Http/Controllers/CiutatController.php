<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\CiutatsResource;
use App\Models\Ciutat;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CiutatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ciutats = Ciutat::with(['pais', 'aeroports', 'transportistas', 'liniasTransporteMaritimo', 'ports'])->get();
        return CiutatsResource::collection($ciutats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ciutat = new Ciutat();
        $ciutat->nom = $request->input('nom');
        $ciutat->pais_id = $request->input('pais_id');

        try{
            $ciutat->save();
            $response = (new CiutatsResource($ciutat))
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
    public function show(Ciutat $ciutat)
    {
        $ciutat = Ciutat::with(['ofertas'])->find($ciutat->id);
        return new CiutatsResource($ciutat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ciutats = Ciutat::find($id);
            if(!$ciutats){
                $response = response()->json([
                    'error' => 'Pais no trobat',
                ], 404);
            }else{
            $ciutats->nom = $request->input('nom');
            $ciutats->pais_id = $request->input('pais_id');

                try{
                    $ciutats->save();
                    $response = (new CiutatsResource($ciutats))
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
            $ciutat = Ciutat::find($id);
            if(!$ciutat){
                $response = response()->json([
                    'error' => 'Pais no trobat',
                ], 404);
            }else{
                $ciutat->delete();
                $response = (new CiutatsResource($ciutat))
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
