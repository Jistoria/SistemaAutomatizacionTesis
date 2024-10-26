<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;

class AuthenticatedException extends Exception
{
    protected function unauthenticated($request, AuthenticationException $exception)
{
    if ($request->expectsJson()) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    return redirect()->guest(route('login'));
}

}
