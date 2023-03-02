<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

        $credenciais = $request->all(['email', 'password']); //retorna uma array[]

        //autenticação (email e senha)
        $token = auth('api')->attempt($credenciais);
        
        if($token){ //usuário autenticado
            return response()->json(['token' => $token]);
        }else{ //erro de senha ou usuario
            return response()->json(['erro' => 'erro de senha ou usuario'], 403);
        }
    }

    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'logout realizado com sucesso']);
    }

    public function refresh(){
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me(){
        return response()->json(auth()->user());
    }
}
