<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DosenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $token1 = Dosen::where('api_token',$request->token)->get();
        $apiKey = $request->header('x-api-key');
    
        if (!$token1->isEmpty() && $apiKey == 'krs_unud') {
            return $next($request);
        }

        // Jika pengguna bukan dosen, Anda dapat mengembalikan respon yang sesuai, misalnya:
        return response()->json(['error' => 'Unauthorized. This action is only allowed for dosen.'], 403);
    }
}
