<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $credentials = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        $credentials['password'] = Hash::make($credentials['password']);

        $usuario = User::create($credentials);

         $token = $usuario->createToken('TokenUsuario')->plainTextToken;
         

         $respuesta = [
             'data' =>[
                 'usuario' => $usuario,
                 'token' => $token
             ],
             
            ];
            return response()->json($respuesta);
    }
}
