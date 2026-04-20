<?php

namespace App\Http\Controllers;

use App\Classes\Utilitat;
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
        $usuaris = Usuari::with(['company', 'rol', 'ofertesComClient', 'ofertesComComercial'])->get();
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
        $usuari->correu = $request->correu;
        $usuari->contrasenya = bcrypt($request->contrasenya);
        $usuari->nom = $request->nom;
        $usuari->cognoms = $request->cognoms;
        $usuari->rol_id = $request->rol_id;
        $usuari->company_id = $request->company_id;
        $usuari->status = 2;

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
        $usuari = Usuari::with(['company', 'rol', 'ofertesComClient', 'ofertesComComercial'])->find($usuari->id);
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
            $usuari->correu = $request->correu;
            $usuari->contrasenya = bcrypt($request->contrasenya);
            $usuari->nom = $request->nom;
            $usuari->cognoms = $request->cognoms;
            $usuari->rol_id = $request->rol_id;
            $usuari->company_id = $request->company_id;

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



    public function updatePerfil(Request $request)
    {
        $usuari = $request->user();

        if (!$usuari) {
            return response()->json([
                'error' => 'Usuari no autenticat'
            ], 401);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'cognoms' => 'required|string|max:255',
            'correu' => 'required|email|max:255',
            'tlfn' => 'nullable|string|max:20',
        ]);

        $usuari->nom = $request->nom;
        $usuari->cognoms = $request->cognoms;
        $usuari->correu = $request->correu;
        $usuari->tlfn = $request->tlfn;

        try {
            $usuari->save();

            return response()->json([
                'message' => 'Perfil actualizado correctamente',
                'user' => $usuari
            ], 200);

        } catch (QueryException $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cambia la contraseña del cliente
     */

    public function changePassword(Request $request)
    {
        $usuari = $request->user();

        if (!$usuari) {
            return response()->json([
                'error' => 'Usuari no autenticat'
            ], 401);
        }

        // Validación
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        // Comprobar contraseña actual
        if (!Hash::check($request->current_password, $usuari->contrasenya)) {
            return response()->json([
                'error' => 'Contraseña actual incorrecta'
            ], 400);
        }

        // Asignación manual (igual que tu estilo)
        $usuari->contrasenya = bcrypt($request->new_password);

        try {
            $usuari->save();

            return response()->json([
                'message' => 'Contraseña actualizada correctamente'
            ], 200);

        } catch (QueryException $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
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

    public function buscarClientes(Request $request)
    {
        try {
            $buscador = $request->query('q');
            $usuaris = ([]);

            if (!$buscador) {
                $usuaris = ([]);
            } else {
                $usuaris = Usuari::where('nom', 'LIKE', "%{$buscador}%")
                    ->limit(100)
                    ->get();
            }

            $response = response()->json($usuaris);
        } catch (\Exception $e) {
            $response = response()->json(['error' => $e->getMessage()], 500);
        }

        return $response;
    }

    public function buscarComercial(Request $request)
    {
        try {
            $buscador = $request->query('q');
            $usuaris = ([]);

            if (!$buscador) {
                $usuaris = ([]);
            } else {
                $usuaris = Usuari::where('nom', 'LIKE', "%{$buscador}%")
                    ->whereHas('rol', function ($query) {
                        $query->where('id', 2);
                    })
                    ->limit(100)
                    ->get();
            }

            $response = response()->json($usuaris);
        } catch (\Exception $e) {
            $response = response()->json(['error' => $e->getMessage()], 500);
        }

        return $response;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout correcto',
        ]);

    }
}
