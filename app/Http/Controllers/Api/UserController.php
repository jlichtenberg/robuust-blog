<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function get()
    {
        $user = auth()->user();
        
        return response()->json([
            'success' => true,
            'user' => $user
        ], 200);
    }
}
