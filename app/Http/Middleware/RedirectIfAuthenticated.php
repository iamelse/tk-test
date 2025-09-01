<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class RedirectIfAuthenticated extends Middleware
{
    /**
     * Override redirect path for unauthenticated users.
     */
    protected function redirectTo($request): ?string
    {
        return $request->expectsJson()
            ? null
            : route('auth.login');
    }
}
