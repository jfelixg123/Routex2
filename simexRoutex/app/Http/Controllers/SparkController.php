<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Http;

class SparkController extends Controller
{
    public function buscar(Request $request)
    {
        $sql = $request->input('query');

    // Ejecutamos beeline directamente (el cliente nativo de Spark)
    // Esto funciona aunque no tengamos drivers instalados en PHP
    $cmd = "docker exec simex-spark beeline -u jdbc:hive2://localhost:10000/default -n user -p pass --showHeader=false --outputformat=csv2 -e \"$sql\" 2>&1";

    $res = shell_exec($cmd);

    // Limpiamos la salida para quedarnos solo con los datos
    $lineas = explode("\n", trim($res));

    return response()->json([
        'resultado' => $lineas,
        'status' => 'success'
    ]);}
}
