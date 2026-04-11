<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\RolResource;
use Illuminate\Database\QueryException;
use App\Clases\Utilitat;

class RolController extends Controller
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
    public function show(Rol $rol)
    {
        $rol = Rol::with(['usuaris'])->find($rol->id);
        return new RolResource($rol);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::find($id);
            if(!$rol){
                $response = response()->json([
                    'error' => 'Usuari no trobat',
                ], 404);
            }else{
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
            }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RolController $id)
    {
        try{
            $usuari = Rol::find($id);
            if(!$usuari){
                $response = response()->json([
                    'error' => 'Usuari no trobat',
                ], 404);
            }else{
                $usuari->delete();
                $response = (new RolResource($usuari))
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
