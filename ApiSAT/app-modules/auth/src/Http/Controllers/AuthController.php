<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;

class AuthController
{
    public function login (){
        return response()->json([
            'message' => 'Login'
        ]);
    }
}
