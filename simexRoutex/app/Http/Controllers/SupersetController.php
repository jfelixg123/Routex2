<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
class SupersetController extends Controller
{
    public function getToken()
    {
        $base_url = 'http://superset:8088';

        // 🔐 1. LOGIN
        $login = Http::post("$base_url/api/v1/security/login", [
            'username' => 'admin',
            'password' => 'admin',
            'provider' => 'db',
            'refresh'  => true,
        ]);

        if (!$login->successful()) {
            return response()->json(['error' => 'Login failed', 'details' => $login->body()], 500);
        }

        $accessToken = $login['access_token'];

        // 🛡️ 2. OBTENER CSRF TOKEN (Paso obligatorio)
        $csrf = Http::withHeaders([
            'Authorization' => "Bearer $accessToken"
        ])->get("$base_url/api/v1/security/csrf_token/");

        if (!$csrf->successful()) {
            return response()->json(['error' => 'CSRF failed'], 500);
        }

        $csrfToken = $csrf['result'];
        // Obtenemos la cookie de sesión para mantener la coherencia
        $cookie = $csrf->header('Set-Cookie');

        // 🎟️ 3. PEDIR GUEST TOKEN
        $guest = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'X-CSRFToken'   => $csrfToken,
            'Cookie'        => $cookie
        ])->post("$base_url/api/v1/security/guest_token/", [
            "resources" => [
                [
                    "type" => "dashboard",
                    "id"   => "8212dbaa-b341-4f72-8580-edadb6088d92" // No el número corto
                ]
            ],
            "rls"  => [],
            "user" => [
                "username"   => "guest_user",
                "first_name" => "Guest",
                "last_name"  => "User"
            ]
        ]);

        if (!$guest->successful()) {
            return response()->json([
                'error'   => 'Guest token failed',
                'status'  => $guest->status(),
                'details' => $guest->json()
            ], 500);
        }

        return response()->json(['token' => $guest['token']]);
    }
}
