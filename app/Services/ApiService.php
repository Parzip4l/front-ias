<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected function getBaseUrl($service)
    {
        return config("services.{$service}.base_url");
    }

    protected function withToken()
    {
        $token = session('api_token');
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ]);
    }
}
