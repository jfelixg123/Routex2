<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\PortResource;
use App\Models\Port;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;


class PortController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $port = Port::with(['pais', 'ofertasOrigen', 'ofertasDestino'])->get();
        return PortResource::collection($port);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $port = new Port();
        $port->nom = $request->input('nom');
        $port->id_ciutat = $request->input('id_ciutat');

        try{
            $port->save();
            $response = (new PortResource($port))
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
    public function show(Port $port)
    {
        $port = Port::with(['pais', 'ofertasOrigen', 'ofertasDestino'])->find($port->id);
        return new PortResource($port);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $port = Port::find($id);
            if(!$port){
                $response = response()->json([
                    'error' => 'Transportista no trobat',
                ], 404);
            }else{
                $port->nom = $request->input('nom');
                $port->id_ciutat = $request->input('id_ciutat');

                try{
                    $port->save();
                    $response = (new PortResource($port))
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
            $port = Port::find($id);
            if(!$port){
                $response = response()->json([
                    'error' => 'Transportista no trobat',
                ], 404);
            }else{
                $port->delete();
                $response = (new PortResource($port))
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
