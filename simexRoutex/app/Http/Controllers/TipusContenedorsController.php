<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\TipusContenedorsResource;
use App\Models\TipusContenedors;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipusContenedorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipusContenedors = TipusContenedors::with(['ofertas'])->get();
        return TipusContenedorsResource::collection($tipusContenedors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipusContenedors = new TipusContenedors();
        $tipusContenedors->tipus = $request->input('tipus');

        try{
            $tipusContenedors->save();
            $response = (new TipusContenedorsResource($tipusContenedors))
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
    public function show(TipusContenedors $tipusContenedors)
    {
        $tipusContenedors = TipusContenedors::with(['ofertas'])->find($tipusContenedors->id);
        return new TipusContenedorsResource($tipusContenedors);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipusContenedors = TipusContenedors::find($id);
            if(!$tipusContenedors){
                $response = response()->json([
                    'error' => 'Tipu de contenidor no trobat',
                ], 404);
            }else{
                $tipusContenedors->tipus = $request->input('tipus');

                try{
                    $tipusContenedors->save();
                    $response = (new TipusContenedorsResource($tipusContenedors))
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
            $tipusContenedors = TipusContenedors::find($id);
            if(!$tipusContenedors){
                $response = response()->json([
                    'error' => 'Tipu de contenidor no trobat',
                ], 404);
            }else{
                $tipusContenedors->delete();
                $response = (new TipusContenedorsResource($tipusContenedors))
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
