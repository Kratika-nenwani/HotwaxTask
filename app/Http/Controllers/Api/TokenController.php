<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function generateSanctumToken() {
       
        $user = User::find(1); 
        $token = $user->createToken('hotwax')->plainTextToken;
        $user->token=$token;
        $user->save();
        return response()->json(['token' => $token]);
    }
}
