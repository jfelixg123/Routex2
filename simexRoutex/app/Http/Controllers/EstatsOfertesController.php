<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\EstatsOfertesResource;
use App\Models\EstatsOfertes;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EstatsOfertesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estatsOfertes = EstatsOfertes::with(['ofertas'])->get();
        return EstatsOfertesResource::collection($estatsOfertes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $estatsOfertes = new EstatsOfertes();
        $estatsOfertes->estat = $request->input('estat');

        try{
            $estatsOfertes->save();
            $response = (new EstatsOfertesResource($estatsOfertes))
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
    public function show(EstatsOfertes $estatsOfertes)
    {
        $estatsOfertes = EstatsOfertes::with(['ofertas'])->find($estatsOfertes->id);
        return new EstatsOfertesResource($estatsOfertes);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $estatsOfertes = EstatsOfertes::find($id);
            if(!$estatsOfertes){
                $response = response()->json([
                    'error' => 'Tipu d estat no trobada',
                ], 404);
            }else{
                $estatsOfertes->estat = $request->input('estat');

                try{
                    $estatsOfertes->save();
                    $response = (new EstatsOfertesResource($estatsOfertes))
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
            $estatsOfertes = EstatsOfertes::find($id);
            if(!$estatsOfertes){
                $response = response()->json([
                    'error' => 'Tipu d estats no trobada',
                ], 404);
            }else{
                $estatsOfertes->delete();
                $response = (new EstatsOfertesResource($estatsOfertes))
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
