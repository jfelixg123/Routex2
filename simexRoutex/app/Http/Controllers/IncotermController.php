<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\IncotermsResource;
use App\Models\Incoterm;
use App\Models\IncotermPaso;
use App\Models\TipoIncoterm;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class IncotermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incoterm = Incoterm::with(['trackingSteps', 'tiposIncoterm', 'ofertas', 'pasosAsociados'])->get();
        return IncotermsResource::collection($incoterm);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $tipo = new TipoIncoterm();
            $tipo->codi = strtoupper($request->codi);
            $tipo->nom = $request->nom;
            $tipo->save();

            $incoterm = new Incoterm();
            $incoterm->tipus_inconterm_id = $tipo->id;
            $incoterm->tracking_steps_id = $request->tracking_steps_id;
            $incoterm->save();

            //Control de si llegan los pasos
            if ($request->has('pasosSeleccionados') && is_array($request->pasosSeleccionados)) {
                foreach ($request->pasosSeleccionados as $key => $stepId) {
                    $intermedia = new IncotermPaso();
                    $intermedia->incoterm_id = $incoterm->id;
                    $intermedia->tracking_step_id = $stepId;
                    $intermedia->orden = $key + 1;
                    $intermedia->save();
                }
            }

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
                try{
                    $tipo = TipoIncoterm::find($incoterm->tipus_inconterm_id);
                    if ($tipo) {
                        $tipo->codi = strtoupper($request->codi);
                        $tipo->nom = $request->nom;
                        $tipo->save();
                    }
                    $incoterm->tracking_steps_id = $request->tracking_steps_id;
                    $incoterm->save();

                      if ($request->has('pasosSeleccionados')) {
                        // 1. Borramos los pasos antiguos para este incoterm
                        IncotermPaso::where('incoterm_id', $incoterm->id)->delete();

                        // 2. Insertamos los nuevos
                        foreach ($request->pasosSeleccionados as $key => $stepId) {
                            $intermedia = new IncotermPaso();
                            $intermedia->incoterm_id = $incoterm->id;
                            $intermedia->tracking_step_id = $stepId;
                            $intermedia->orden = $key + 1;
                            $intermedia->save();
                        }
                    }

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
