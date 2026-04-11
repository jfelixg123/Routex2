<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\TipusCarregaResource;
use App\Models\TipusCarrega;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipusCarregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipusCarrega = TipusCarrega::with(['ofertas'])->get();
        return TipusCarregaResource::collection($tipusCarrega);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipusCarrega = new TipusCarrega();
        $tipusCarrega->tipus = $request->input('tipus');

        try{
            $tipusCarrega->save();
            $response = (new TipusCarregaResource($tipusCarrega))
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
    public function show(TipusCarrega $tipusCarrega)
    {
        $tipusCarrega = TipusCarrega::with(['ofertas'])->find($tipusCarrega->id);
        return new TipusCarregaResource($tipusCarrega);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipusCarrega = TipusCarrega::find($id);
            if(!$tipusCarrega){
                $response = response()->json([
                    'error' => 'Tipu de càrrega no trobada',
                ], 404);
            }else{
                $tipusCarrega->tipus = $request->input('tipus');

                try{
                    $tipusCarrega->save();
                    $response = (new TipusCarregaResource($tipusCarrega))
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
            $tipusCarrega = TipusCarrega::find($id);
            if(!$tipusCarrega){
                $response = response()->json([
                    'error' => 'Tipu de càrrega no trobada',
                ], 404);
            }else{
                $tipusCarrega->delete();
                $response = (new TipusCarregaResource($tipusCarrega))
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
