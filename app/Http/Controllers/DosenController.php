<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class DosenController extends Controller
{
    //
    // public function __construct()
    // {
    //     RateLimiter::for('profile', function ($request) {
    //         $token = $request->token; // Anda dapat menyesuaikan ini sesuai dengan cara token dikirimkan
    //         return Limit::perMinute(3)->by($token);
    //     });
    // }

    public function LoginDosen(Request $request){

        $request->validate([
            'email' => 'required',
            'password'=> 'required',
        ]);

        if (Auth::guard('dosen')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::guard('dosen');
            if ($auth instanceof \Illuminate\Contracts\Auth\StatefulGuard) {
                $res = $this->myPrivateMethod('login',$request->all());
                    return response()->json([
                        'response_code' => 200,
                        'message' => 'Login Berhasil',
                        'conntent' => $res[0],
                        'data' => $res[1],
                        'token' => $res[1]['api_token'],
                    ]);
                }else {
                    return response()->json([
                        'response_code' => 404,
                        'message' => 'Username atau Password Tidak Valid!'
                    ]);
                }
                
        }else{
            return response()->json([
                'response_code' => 404,
                'message' => 'Username atau Password Tidak Valid!'
            ]);
        }
        
    }

    public function profile(Request $request){
        $datas = $this->myPrivateMethod('get_data',$request->all());
        if(!$datas->isEmpty()){
            return response()->json([
                'data'=>$datas,
                'tes'=>'ok',
            ]);
        }else{
            return response()->json([
                'data'=>'anda belum login'
            ]);
        }
    }
    
    public function logout(Request $request){
        $res = $this->myPrivateMethod('logouts',$request->all());
        return response()->json([
            'response_code' => 200,
            'nama'=>$res[0],
            'message' => "Berhasil Logout",
        ]);
    }

    public function addSiswa(Request $request){
        $user = new User();
        $request->validate([
            'email' => 'required',
            'password'=> 'required',
        ]);
        $check = User::where('email',$request->email)->orWhere('nim',$request->nim)->get();
        if($check->isEmpty()){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->nim = $request->nim;
            $user->password = $nim.'unud';
            $user->save();
        }
        
    }




    
    private function myPrivateMethod($val1,$val2)
    {
        if($val1=='login'){
            $dosen = Auth::guard('dosen')->user();
            $token = $dosen->createToken('loginD')->plainTextToken;
            $dosen->api_token = $token;
            $dosen->update();
            return [$token,$dosen];
        }elseif($val1=='logouts'){
            $data = Dosen::where('api_token',$val2['token'])->first();
            $user = Dosen::find($data->id);
            $user->api_token = null;
            $user->update();    
            return [$user];
        }elseif($val1=='get_data'){
            $datas = Dosen::where('api_token',$val2['token'])->get();
            return $datas;
        }else{
            $datas = Dosen::where('api_token',$request->token)->get();
            return 'gagal';
        }
    }
}
