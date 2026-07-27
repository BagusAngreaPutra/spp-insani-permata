<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // Jika route yang diakses diawali dengan "siswa"
            if ($request->is('siswa') || $request->is('siswa/*')) {
                return route('siswa.login');
            }

            // Default: admin
            return route('login');
        }

        return null;
    }
}
