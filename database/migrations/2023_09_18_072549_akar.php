<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Akar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('akar', function (Blueprint $table) {
            $table->id();
            //$table->morphs('tokenable');
            $table->unsignedBigInteger('input');
            $table->unsignedBigInteger('angka');
            $table->unsignedBigInteger('waktu');
            $table->string('jenis');
            //$table->string('token', 64)->unique();
            //$table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
