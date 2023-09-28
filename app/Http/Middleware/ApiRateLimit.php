<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Http\Response;
class ApiRateLimit extends ThrottleRequests

{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // protected function resolveRequestSignature($request)
    // {
    //     return sha1($request->user()->id);
    // }
    public function handle($request, Closure $next, $maxAttempts = 3, $decayMinutes = 1, $prefix = '')
    {
        // Ambil pengaturan dari file konfigurasi rate_limits.php
        $rateLimitConfig = config('rate_limits.default');
        // Set jumlah percobaan dan batas waktu berdasarkan konfigurasi
        $maxAttempts = $rateLimitConfig['limit'];
        $decayMinutes = $rateLimitConfig['expires'];
        $response = parent::handle($request, $next, $maxAttempts, $decayMinutes);
        // Lanjutkan ke middleware berikutnya
        // if ($response->getStatusCode() === Response::HTTP_TOO_MANY_REQUESTS) {
        //     $token = $request->token;
        //     $user= User::where('api_token',$token)->first();
        //     $dosen = Dosen::where('api_token',$token)->first();
        //     if(!$user->isEmpty() && $dosen->isEmpty()){
        //         $siswa = User::where('api_token',$token)->first();
        //         $user = User::find($siswa->id);
        //         $user->api_token = null;
        //         $user->update();  
        //     }else if(!$dosen->isEmpty() && $user->isEmpty()){
        //         $data = Dosen::where('api_token',$token)->first();
        //         $user = Dosen::find($data->id);
        //         $user->api_token = null;
        //         $user->update();  
        //     }
        //     return response()->json([
        //         'message' => 'Rate limit exceeded. Your access has been restricted.'
        //     ], Response::HTTP_TOO_MANY_REQUESTS);
        // }else 
        if ($response->getStatusCode() === 429) {
            // Pesan respons kustom
            $token = $request->token;
            $user= User::where('api_token',$token)->first();
            $dosen = Dosen::where('api_token',$token)->first();
            if(!$user->isEmpty() && $dosen->isEmpty()){
                $siswa = User::where('api_token',$token)->first();
                $user = User::find($siswa->id);
                $user->api_token = null;
                $user->update();  
            }else if(!$dosen->isEmpty() && $user->isEmpty()){
                $data = Dosen::where('api_token',$token)->first();
                $user = Dosen::find($data->id);
                $user->api_token = null;
                $user->update();  
            }
            $customMessage = [
                'message' => 'Rate limit exceeded. Please try again later.'
            ];

            // Mengubah kode status dan isi pesan respons
            $response->setStatusCode(429);
            $response->setContent(json_encode($customMessage));
        }else{
            return $response;
        }
    
    }
}
