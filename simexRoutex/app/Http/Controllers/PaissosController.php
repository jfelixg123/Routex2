<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaissosResources;
use App\Models\Paissos;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PaissosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paissos = Paissos::with(['ciutats'])->get();
        return PaissosResources::collection($paissos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $paissos = new Paissos();
        $paissos->nom = $request->input('nom');

        try{
            $paissos->save();
            $response = (new PaissosResources($paissos))
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
    public function show(Paissos $paissos)
    {
        $paissos = Paissos::with(['ofertas'])->find($paissos->id);
        return new PaissosResources($paissos);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $paissos = Paissos::find($id);
            if(!$paissos){
                $response = response()->json([
                    'error' => 'Pais no trobat',
                ], 404);
            }else{
                $paissos->nom = $request->input('nom');

                try{
                    $paissos->save();
                    $response = (new PaissosResources($paissos))
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
            $paissos = Paissos::find($id);
            if(!$paissos){
                $response = response()->json([
                    'error' => 'Pais no trobat',
                ], 404);
            }else{
                $paissos->delete();
                $response = (new PaissosResources($paissos))
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
