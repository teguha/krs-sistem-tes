<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse {
      
        $request->validate([
            'email' => 'required',
            'password'=> 'required',
        ]);

        $login = Auth::Attempt($request->all());
        if (!$login){
            return response()->json([
                'response_code' => 404,
                'message' => 'Username atau Password Tidak Ditemukan!'
            ]);
        }else{
            $user = Auth::User();
            $token = $user->createToken('login')->plainTextToken;
            $user->api_token = $token;
            $user->update();
            return response()->json([
                'response_code' => 200,
                'message' => 'Login Berhasil',
                'conntent' => $token
            ]);
        }
    }
 
    public function profile(Request $request){
        $apiKey = $request->header('x-api-key');
        if ($apiKey != null){
            $datas = User::where('api_token',$request->token)->get();
            if(!$datas->isEmpty()){
                return response()->json([
                    'data'=>$datas,
                    'tes'=>'ok',
                ]);
            }else{
                return response()->json([
                    'data'=>'api token kosong'
                ]);
            }
        }else{
            return response()->json([
                'data'=>'token api key tidak valid'
            ]);
        }
    } 

    public function logout(Request $request){
        $apiKey = $request->header('x-api-key');
        if($apiKey != null){
            $data = User::where('api_token',$request->token)->first();
            if($data !=null){
                $user = User::find($data->id);
                $user->api_token = null;
                $user->update();
                //Auth::User()->currentAccessToken()->delete();
                return response()->json([
                    'response_code' => 200,
                    'message' => 'Logout Berhasil',
                ]);
            }else{
                return response()->json([
                    'message' => 'Login Terlebih dahulu',
                ]);
            }
        }else{
            return response()->json([
                'data'=>'token api key tidak valid'
            ]);
        }
    }

}
