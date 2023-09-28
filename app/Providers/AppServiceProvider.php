<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;
use App\Models\Dosen;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // RateLimiter::for('profile', function (Request $request) {
        //     return Limit::perMinute(3)->by($request->token);
        // });
        RateLimiter::for('profile', function ($request) {
            $token = $request->token;

            // $limit = Limit::perMinute(3);
            if (RateLimiter::tooManyAttempts('profile:' . $token, 2)) {
                return null;
            }else{
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
                return response()->json(['message' => 'Rate limit for profile requests exceeded. Please try again later.'], 429);
                // return null;
            }

        });
    }
}
