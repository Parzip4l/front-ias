<?php

namespace App\Services;

class AuthService extends ApiService
{
    public function login($email, $password)
    {
        $baseUrl = $this->getBaseUrl('auth');
        return \Http::post("{$baseUrl}/auth/login", [
            'email' => $email,
            'password' => $password
        ])->json();
    }

    public function me()
    {
        $baseUrl = $this->getBaseUrl('auth');
        return $this->withToken()->get("{$baseUrl}/auth/me")->json();
    }

    public function logout()
    {
        $baseUrl = $this->getBaseUrl('auth');
        return $this->withToken()->post("{$baseUrl}/auth/logout")->json();
    }
}
