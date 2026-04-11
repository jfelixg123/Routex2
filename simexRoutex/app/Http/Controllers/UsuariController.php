<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Resources\UsuariResource;
use App\Models\Usuari;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuaris = Usuari::with(['company', 'rol'])->get();
        return UsuariResource::collection($usuaris);
    }

    public function login(Request $request)
    {
        $user = Usuari::where('correu', $request->input('correu'))->first();

        if ($user && Hash::check($request->input('contrasenya'), $user->contrasenya)) {

            $token = $user->createToken('api-token')->plainTextToken;
            $response = response()->json([
                'usuari' => new UsuariResource($user),
                'token' => $token,
            ], 200);

        } else {
            $response = response()->json([
                'error' => 'Credencials incorrectes.',
            ], 400);
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuari = new Usuari();
        $usuari->correu = $request->input('correu');
        $usuari->contrasenya = $request->input('contrasenya');
        $usuari->nom = $request->input('nom');
        $usuari->cognoms = $request->input('cognoms');
        $usuari->rol_id = $request->input('rol_id');
        $usuari->company_id = $request->input('company_id');

        try {
            $usuari->save();
            $response = (new UsuariResource($usuari))
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            $missatge = Utilitat::errorMessage($e);
            $response = response()->json([
                'error' => $missatge
            ], 400);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuari $usuari)
    {
        $usuari = Usuari::with(['company', 'rol'])->find($usuari->id);
        return new UsuariResource($usuari);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuari = Usuari::find($id);
        if (!$usuari) {
            $response = response()->json([
                'error' => 'Usuari no trobat',
            ], 404);
        } else {
            $usuari->correu = $request->input('correu');
            $usuari->contrasenya = $request->input('contrasenya');
            $usuari->nom = $request->input('nom');
            $usuari->cognoms = $request->input('cognoms');
            $usuari->rol_id = $request->input('rol_id');
            $usuari->company_id = $request->input('company_id');

            try {
                $usuari->save();
                $response = (new UsuariResource($usuari))
                    ->response()
                    ->setStatusCode(201);
            } catch (QueryException $e) {
                $missatge = Utilitat::errorMessage($e);
                $response = response()->json([
                    'error' => $missatge
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
        try {
            $usuari = Usuari::find($id);
            if (!$usuari) {
                $response = response()->json([
                    'error' => 'Usuari no trobat',
                ], 404);
            } else {
                $usuari->delete();
                $response = (new UsuariResource($usuari))
                    ->response()
                    ->setStatusCode(200);
            }
        } catch (QueryException $e) {
            $missatge = Utilitat::errorMessage($e);
            $response = response()->json([
                'error' => $missatge
            ], 400);
        }

        return $response;
    }

    public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout correcto'
    ]);
}
}
