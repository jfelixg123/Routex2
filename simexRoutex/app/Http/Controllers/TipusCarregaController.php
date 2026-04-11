<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RolResource;
use App\Models\TipusCarrega;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Clases\Utilitat;

class TipusCarregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rols = Rol::with(['usuaris'])->get();
        return RolResource::collection($rols);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rol = new Rol();
        $rol->rol = $request->input('rol');

        try{
            $rol->save();
            $response = (new RolResource($rol))
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
    public function show(TipusCarrega $tipusCarrega)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipusCarrega $tipusCarrega)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipusCarrega $tipusCarrega)
    {
        //
    }
}
