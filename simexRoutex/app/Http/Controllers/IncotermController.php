<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\IncotermsResource;
use App\Models\Incoterm;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class IncotermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incoterm = Incoterm::with(['trackingSteps', 'tiposIncoterm', 'ofertas'])->get();
        return IncotermsResource::collection($incoterm);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $incoterm = new Incoterm();
        $incoterm->tipus_inconterm_id = $request->input('tipus_inconterm_id');
        $incoterm->tracking_steps_id = $request->input('tracking_steps_id');

        try{
            $incoterm->save();
            $response = (new IncotermsResource($incoterm))
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
    public function show(Incoterm $incoterm)
    {
        $incoterm = Incoterm::with(['trackingSteps', 'tiposIncoterm', 'ofertas'])->find($incoterm->id);
        return new IncotermsResource($incoterm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $incoterm = Incoterm::find($id);
            if(!$incoterm){
                $response = response()->json([
                    'error' => 'Tipu incoterm no trobat',
                ], 404);
            }else{
                $incoterm->tipus_inconterm_id = $request->input('tipus_inconterm_id');
                $incoterm->tracking_steps_id = $request->input('tracking_steps_id');

                try{
                    $incoterm->save();
                    $response = (new IncotermsResource($incoterm))
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
            $incoterm = Incoterm::find($id);
            if(!$incoterm){
                $response = response()->json([
                    'error' => 'Tipu incoterm no trobat',
                ], 404);
            }else{
                $incoterm->delete();
                $response = (new IncotermsResource($incoterm))
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
