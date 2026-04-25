<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\OfertaSeguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PasosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ofertas = Oferta::with(['usuari', 'portOrigen', 'portDesti', 'estatsOfertes'])
            ->whereIn('estat_oferta_id', [5, 6]) // Asumiendo 5=Aceptada, 6=En Tránsito
            ->get();
        return response()->json($ofertas);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getPasos($id)
    {
        $pasos = OfertaSeguimiento::with('step')
            ->where('oferta_id', $id)
            ->orderBy('orden', 'asc')
            ->get();
        return response()->json($pasos);
    }

    public function checkPaso(Request $request, $id)
    {
        try {
            $paso = OfertaSeguimiento::findOrFail($id);

            // Convertimos el bool a entero
            $paso->esta_completado = $request->completado ? 1 : 0;
            $paso->fecha_completado = $request->completado ? date('Y-m-d H:i:s') : null;
            $paso->save();

            return response()->json(['status' => 'OK', 'valor' => $paso->esta_completado]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function subirDocumento(Request $request, $id) // El nombre $id debe coincidir con {id} en la ruta
    {
        $seguimiento = OfertaSeguimiento::find($id);

        if (!$seguimiento) {
            return response()->json(['error' => 'No se encuentra el seguimiento con ID ' . $id], 404);
        }

        if ($request->hasFile('documento')) {
            $file = $request->file('documento');
            // Tu lógica para guardar el archivo...
            $path = $file->store('documentos_seguimiento', 'public');

            $seguimiento->documento_path = $path;
            $seguimiento->save();

            return response()->json(['message' => 'Subido correctamente', 'path' => $path]);
        }

        return response()->json(['error' => 'No se ha enviado ningún archivo'], 400);
    }
    public function borrarDocumento($id) {
        $paso = OfertaSeguimiento::find($id);

        if ($paso && $paso->documento_path) {
            Storage::disk('public')->delete($paso->documento_path);

            $paso->documento_path = null;
            $paso->save();

            return response()->json(['message' => 'Documento eliminado'], 200);
        }

        return response()->json(['error' => 'No hay documento'], 404);
    }
}
