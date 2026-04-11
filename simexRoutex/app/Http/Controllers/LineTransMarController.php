<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\LineTransMarResource;
use App\Models\LineTransMar;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class LineTransMarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lineas = LineTransMar::with(['ciutat', 'ofertas'])->get();
        return LineTransMarResource::collection($lineas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $line = new LineTransMar();
        $line->nom = $request->input('nom');
        $line->ciutat_id = $request->input('ciutat_id');

        try{
            $line->save();
            $response = (new LineTransMarResource($line))
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
    public function show(LineTransMar $lineTransMar)
    {
        $lines = LineTransMar::with(['ofertas'])->find($lineTransMar    ->id);
        return new LineTransMarResource($lines);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $line = LineTransMar::find($id);
            if(!$line){
                $response = response()->json([
                    'error' => 'Pais no trobat',
                ], 404);
            }else{
                $line->nom = $request->input('nom');
                $line->ciutat_id = $request->input('ciutat_id');

                try{
                    $line->save();
                    $response = (new LineTransMarResource($line))
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
            $line = LineTransMar::find($id);
            if(!$line){
                $response = response()->json([
                    'error' => 'Línia no trobada',
                ], 404);
            }else{
                $line->delete();
                $response = (new LineTransMarResource($line))
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
