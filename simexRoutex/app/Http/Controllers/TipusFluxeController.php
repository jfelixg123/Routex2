<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\TipusFluxeResource;
use App\Models\TipusFluxe;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipusFluxeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipusFluxe = TipusFluxe::with(['ofertas'])->get();
        return TipusFluxeResource::collection($tipusFluxe);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipusFluxe = new TipusFluxe();
        $tipusFluxe->tipus = $request->input('tipus');

        try{
            $tipusFluxe->save();
            $response = (new TipusFluxeResource($tipusFluxe))
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
    public function show(TipusFluxe $tipusFluxe)
    {
        $tipusFluxe = TipusFluxe::with(['ofertas'])->find($tipusFluxe->id);
        return new TipusFluxeResource($tipusFluxe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipusFluxe = TipusFluxe::find($id);
            if(!$tipusFluxe){
                $response = response()->json([
                    'error' => 'Tipu de fluxe no trobat',
                ], 404);
            }else{
                $tipusFluxe->tipus = $request->input('tipus');

                try{
                    $tipusFluxe->save();
                    $response = (new TipusFluxeResource($tipusFluxe))
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
            $tipusFluxe = TipusFluxe::find($id);
            if(!$tipusFluxe){
                $response = response()->json([
                    'error' => 'Tipu de fluxe no trobat',
                ], 404);
            }else{
                $tipusFluxe->delete();
                $response = (new TipusFluxeResource($tipusFluxe))
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
