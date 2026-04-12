<?php

namespace App\Http\Controllers;

use App\Models\NotificacionDestinatario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificacionDestinatarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NotificacionDestinatario $notificacionDestinatario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $destinatario = NotificacionDestinatario::findOrFail($id);
        $destinatario->update(['leida' => 1]);
        return response()->json($destinatario, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotificacionDestinatario $notificacionDestinatario)
    {
        //
    }

    // Listar notificaciones para un usuario específico (para su campana de avisos)
    public function mostrarPorUsuario($userId)
    {
        $notificaciones = NotificacionDestinatario::where('user_id', $userId)
            ->with('notificacion.tipo')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($notificaciones, 200);
    }
}
