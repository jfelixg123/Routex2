<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\TipusValidacionsResource;
use App\Models\TipusValidacions;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipusValidacionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipusValidacions = TipusValidacions::with(['ofertas'])->get();
        return TipusValidacionsResource::collection($tipusValidacions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipusValidacions = new TipusValidacions();
        $tipusValidacions->tipus = $request->input('tipus');

        try{
            $tipusValidacions->save();
            $response = (new TipusValidacionsResource($tipusValidacions))
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
    public function show(TipusValidacions $tipusValidacions)
    {
        $tipusValidacions = TipusValidacions::with(['ofertas'])->find($tipusValidacions->id);
        return new TipusValidacionsResource($tipusValidacions);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipusValidacions = TipusValidacions::find($id);
            if(!$tipusValidacions){
                $response = response()->json([
                    'error' => 'Tipu de validació no trobada',
                ], 404);
            }else{
                $tipusValidacions->tipus = $request->input('tipus');

                try{
                    $tipusValidacions->save();
                    $response = (new TipusValidacionsResource($tipusValidacions))
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
            $tipusValidacions = TipusValidacions::find($id);
            if(!$tipusValidacions){
                $response = response()->json([
                    'error' => 'Tipu de validació no trobada',
                ], 404);
            }else{
                $tipusValidacions->delete();
                $response = (new TipusValidacionsResource($tipusValidacions))
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
