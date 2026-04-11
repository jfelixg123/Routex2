<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $industry = Industry::with(['companies'])->get();
        return IndustryResource::collection($industry);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $industry = new Industry();
        $industry->categoria = $request->input('categoria');

        try{
            $industry->save();
            $response = (new IndustryResource($industry))
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
    public function show(Industry $industry)
    {
        $industry = Industry::with(['companies'])->find($industry->id);
        return new IndustryResource($industry);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $industry = Industry::find($id);
            if(!$industry){
                $response = response()->json([
                    'error' => 'Industria no trobada',
                ], 404);
            }else{
                $industry->categoria = $request->input('categoria');

                try{
                    $industry->save();
                    $response = (new IndustryResource($industry))
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
            $industry = Industry::find($id);
            if(!$industry){
                $response = response()->json([
                    'error' => 'ndustria no trobada',
                ], 404);
            }else{
                $industry->delete();
                $response = (new IndustryResource($industry))
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
