<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' =>['required', 'email'],
            'password' => ['required'],
        ]);
    if (Auth::attempt($credentials)){
        $usuarioLogeado = Auth::user();
        $token = $usuarioLogeado->createToken('TokenUsuario')->plainTextToken;
        $respuesta = [
          'data' =>[
              'usuario' => $usuarioLogeado,
              'token' => $token
          ],
        ];
        return response()->json($respuesta);
    }else{
        return response()->json(['error'=>'Unauthorised'], 401);
    }
    } 
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
    public function logout(){
        Auth::user()->tokens()->delete();
        return response()->json(['mensajes'=>'Usuario desconectado']);
    }
}
