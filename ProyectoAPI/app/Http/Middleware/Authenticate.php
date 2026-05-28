<?php

namespace App\Http\Middleware; // <-- ¡ESTA ES LA LÍNEA CORREGIDA!

use Illuminate\Auth\Middleware\Authenticate as Middleware;
// No necesitamos 'use Illuminate\Http\Request;'

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        return route('login');
    }
}