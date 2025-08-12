<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class JwtSessionAuth
{
    public function handle($request, Closure $next)
    {
        // Cek apakah token ada di session
        if (!Session::has('jwt_token')) {
            return redirect()->route('login')->withErrors([
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        return $next($request);
    }
}
