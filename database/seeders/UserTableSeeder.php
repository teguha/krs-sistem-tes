<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;


#install uuid
//composer require ramsey/uuid

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'admin',
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(), // Verifikasi email otomatis
            'password' => Hash::make('password'), // Gantilah dengan kata sandi yang Anda inginkan
            'remember_token' => Str::random(10),
        ]);
    }
}
