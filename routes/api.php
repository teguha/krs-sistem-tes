<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DosenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login/siswa',[LoginController::class,'login']);
Route::post('login/dosen',[DosenController::class,'loginDosen']);

Route::middleware('auth:sanctum')->group(function(){
    #middleware dosen
    Route::middleware('dosen')->group(function(){
        Route::post('logout/dosen',[DosenController::class,'logout']);
        Route::post('profile/dosen',[DosenController::class,'profile']);
        //->middleware('throttle:profile');
    });
    
    Route::middleware('auth')->group(function(){
        Route::post('profile/siswa',[LoginController::class,'profile']);
        Route::post('logout/siswa',[LoginController::class,'logout']);
        //Route::post('/addUser',[LoginController::class,'addUser']);
    });
    
});

//Route::get('/tes',[LoginController::class,'tes']);


// if (Auth::guard('dosen')->check()) {
//     // Pengguna dengan guard 'dosen' sedang diautentikasi.
// }



//get ->untuk ambil data
//post ->untuk kirim data
//patch ->memperbarui sebagian data , contoh edit profil
//put ->memperbarui seluruh informasi data
//delete -> menghapus data

