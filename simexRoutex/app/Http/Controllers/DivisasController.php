<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\DivisasResource;
use App\Models\Divisas;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DivisasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisas = Divisas::with(['ofertas'])->get();
        return DivisasResource::collection($divisas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $divisas = new Divisas();
        $divisas->tipus = $request->input('tipus');

        try{
            $divisas->save();
            $response = (new DivisasResource($divisas))
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
    public function show(Divisas $divisas)
    {
        $divisas = Divisas::with(['ofertas'])->find($divisas->id);
        return new DivisasResource($divisas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $divisas = Divisas::find($id);
            if(!$divisas){
                $response = response()->json([
                    'error' => 'Tipu d estat no trobada',
                ], 404);
            }else{
                $divisas->tipus = $request->input('tipus');

                try{
                    $divisas->save();
                    $response = (new DivisasResource($divisas))
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
            $divisas = Divisas::find($id);
            if(!$divisas){
                $response = response()->json([
                    'error' => 'Tipu d estats no trobada',
                ], 404);
            }else{
                $divisas->delete();
                $response = (new DivisasResource($divisas))
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
