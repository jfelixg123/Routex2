<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Http\Controllers\Controller;
use App\Models\NotificacionDestinatario;
use App\Models\Usuari;
use DB;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $historial = Notificacion::with(['tipo', 'destinatarios.usuario', 'destinatarios.empresa'])
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($historial, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'mensaje' => 'required',
            'tipo_id' => 'required',
            'target_type' => 'required' // 'empresa' o 'individual'
        ]);

         return DB::transaction(function () use ($request) {
            try {
                // 1. Crear la notificación de forma manual (sin create masivo)
                $notif = new Notificacion();
                $notif->titulo = $request->titulo;
                $notif->mensaje = $request->mensaje;
                $notif->tipo_id = $request->tipo_id;
                $notif->emisor_id = $request->emisor_id ?? 1;
                $notif->save(); // Al usar save() campo a campo, NO necesitas el fillable

                // 2. Lógica de reparto a destinatarios
                if ($request->target_type === 'empresa') {
                    $usuarios = Usuari::where('company_id', $request->company_id)->get();
                    foreach ($usuarios as $u) {
                        $dest = new NotificacionDestinatario();
                        $dest->notificacion_id = $notif->id;
                        $dest->user_id = $u->id;
                        $dest->company_id = $request->company_id;
                        $dest->leida = 0;
                        $dest->save();
                    }
                } else {
                    // Envío individual
                    $dest = new NotificacionDestinatario();
                    $dest->notificacion_id = $notif->id;
                    $dest->user_id = $request->user_id;
                    $dest->company_id = null;
                    $dest->leida = 0;
                    $dest->save();
                }

                return response()->json($notif, 201);

            } catch (\Exception $e) {
                return response()->json(['error' => 'Error al procesar el envío: ' . $e->getMessage()], 500);
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Notificacion $notificacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $notif = Notificacion::findOrFail($id);
            $notif->titulo = $request->titulo;
            $notif->mensaje = $request->mensaje;
            $notif->tipo_id = $request->tipo_id;
            $notif->save();

            return response()->json($notif, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $notif = Notificacion::find($id);

            if (!$notif) {
                return response()->json(['error' => 'No encontrada'], 404);
            }
            NotificacionDestinatario::where('notificacion_id', $id)->delete();
            $notif->delete();

            return response()->json(null, 204);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error SQL: ' . $e->getMessage()], 500);
        }
    }
}
