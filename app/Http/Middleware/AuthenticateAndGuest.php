<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateAndGuest extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
    }
}
