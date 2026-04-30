<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SupersetController extends Controller
{
    /**
     * Método que obtiene un guest token desde Superset
     */
    public function getToken()
    {
        // URL base del servidor de Superset (normalmente en Docker)
        $base_url = 'http://superset:8088';

        // Se envía una petición POST al endpoint de login
        $login = Http::post("$base_url/api/v1/security/login", [
            'username' => 'admin',   // Usuario de Superset
            'password' => 'admin',   // Contraseña
            'provider' => 'db',      // Tipo de autenticación (base de datos interna)
            'refresh'  => true,      // Solicita también refresh token
        ]);

        // Si el login falla, devolvemos error con detalles
        if (!$login->successful()) {
            return response()->json([
                'error' => 'Login failed',
                'details' => $login->body()
            ], 500);
        }

        // Extraemos el access_token (JWT) de la respuesta
        $accessToken = $login['access_token'];

        // Superset requiere CSRF token para operaciones POST
        $csrf = Http::withHeaders([
            'Authorization' => "Bearer $accessToken" // Se envía el token de login
        ])->get("$base_url/api/v1/security/csrf_token/");

        // Si falla la obtención del CSRF, devolvemos error
        if (!$csrf->successful()) {
            return response()->json([
                'error' => 'CSRF failed'
            ], 500);
        }

        // Guardamos el token CSRF (necesario para la siguiente petición)
        $csrfToken = $csrf['result'];

        // Guardamos la cookie de sesión que devuelve Superset
        // Esto es clave para mantener la sesión activa
        $cookie = $csrf->header('Set-Cookie');

        // Se envía otra petición POST con todos los headers necesarios
        $guest = Http::withHeaders([
            'Authorization' => "Bearer $accessToken", // Autenticación JWT
            'X-CSRFToken'   => $csrfToken,            // Token CSRF obligatorio
            'Cookie'        => $cookie                // Cookie de sesión
        ])->post("$base_url/api/v1/security/guest_token/", [

            // Recursos a los que tendrá acceso el usuario guest
            "resources" => [
                [
                    "type" => "dashboard", // Tipo de recurso
                    "id"   => "8212dbaa-b341-4f72-8580-edadb6088d92"
                    // ID del dashboard (UUID, no el ID numérico)
                ]
            ],

            // Reglas de seguridad a nivel de filas (vacío en este caso)
            "rls"  => [],

            // Información del usuario invitado
            "user" => [
                "username"   => "guest_user",
                "first_name" => "Guest",
                "last_name"  => "User"
            ]
        ]);

        // Si falla la generación del guest token
        if (!$guest->successful()) {
            return response()->json([
                'error'   => 'Guest token failed',
                'status'  => $guest->status(),
                'details' => $guest->json()
            ], 500);
        }

        // Devolvemos el token que servirá para incrustar dashboards
        return response()->json([
            'token' => $guest['token']
        ]);
    }
}