<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * cara buat seeder
     * php artisan make:seeder DosenSeeder
     * cara jalankan seeder
     * php artisan db:seed DosenSeeder
     */

    public function run()
    {
        //
        DB::table('dosen')->insert([
            'name' => 'dosen',
            'email' => 'dosen@gmail.com',
            'email_verified_at' => now(), // Verifikasi email otomatis
            'password' => Hash::make('password'), // Gantilah dengan kata sandi yang Anda inginkan
            'remember_token' => Str::random(10),
        ]);
    }
}
