<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\TipoIncotermResource;
use App\Models\TipoIncoterm;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipoIncotermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipoIncoterm = TipoIncoterm::with(['incoterms'])->get();
        return TipoIncotermResource::collection($tipoIncoterm);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipoIncoterm = new TipoIncoterm();
        $tipoIncoterm->codi = $request->input('codi');
        $tipoIncoterm->nom = $request->input('nom');

        try{
            $tipoIncoterm->save();
            $response = (new TipoIncotermResource($tipoIncoterm))
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
    public function show(TipoIncoterm $tipoIncoterm)
    {
        $tipoIncoterm = TipoIncoterm::with(['ofertas'])->find($tipoIncoterm->id);
        return new TipoIncotermResource($tipoIncoterm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipoIncoterm = TipoIncoterm::find($id);
            if(!$tipoIncoterm){
                $response = response()->json([
                    'error' => 'Tipu de incoterm no trobat',
                ], 404);
            }else{
                $tipoIncoterm->codi = $request->input('codi');
                $tipoIncoterm->nom = $request->input('nom');

                try{
                    $tipoIncoterm->save();
                    $response = (new TipoIncotermResource($tipoIncoterm))
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
            $tipoIncoterm = TipoIncoterm::find($id);
            if(!$tipoIncoterm){
                $response = response()->json([
                    'error' => 'Tipu de incoterm no trobat',
                ], 404);
            }else{
                $tipoIncoterm->delete();
                $response = (new TipoIncotermResource($tipoIncoterm))
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
