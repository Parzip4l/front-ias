<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SppdApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SPPD_API_URL'), '/');
    }

    public function login($email, $password)
    {
        $response = Http::post("{$this->baseUrl}/auth/login", [
            'email'    => $email,
            'password' => $password
        ]);

        return $response->json();
    }
}
