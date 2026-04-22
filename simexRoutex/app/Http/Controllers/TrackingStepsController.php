<?php

namespace App\Http\Controllers;

use App\Classes\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrackingStepsResource;
use App\Models\TrackingSteps;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TrackingStepsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trackingsteps = TrackingSteps::with(['incoterms'])->get();
        return TrackingStepsResource::collection($trackingsteps);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $trackingsteps = new TrackingSteps();
        $trackingsteps->ordre = $request->input('ordre');
        $trackingsteps->nom = $request->input('nom');

        try{
            $trackingsteps->save();
            $response = (new TrackingStepsResource($trackingsteps))
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
    public function show(TrackingSteps $trackingsteps)
    {
        $trackingsteps = TrackingSteps::with(['ofertas'])->find($trackingsteps->id);
        return new TrackingStepsResource($trackingsteps);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $trackingsteps = TrackingSteps::find($id);
            if(!$trackingsteps){
                $response = response()->json([
                    'error' => 'Tipu de tracking step no trobada',
                ], 404);
            }else{
                $trackingsteps->ordre = $request->input('ordre');
                $trackingsteps->nom = $request->input('nom');

                try{
                    $trackingsteps->save();
                    $response = (new TrackingStepsResource($trackingsteps))
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
            $trackingsteps = TrackingSteps::find($id);
            if(!$trackingsteps){
                $response = response()->json([
                    'error' => 'Tipu de validació no trobada',
                ], 404);
            }else{
                $trackingsteps->delete();
                $response = (new TrackingStepsResource($trackingsteps))
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
