<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Officetiming extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officetiming', function (Blueprint $table) {
            $table->id();
            $table->time('timing_start')->nullable();
            $table->time('timing_off')->nullable();
            $table->time('break')->nullable();
            $table->string('totalworkinghours')->nullable();
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
        Schema::dropIfExists('officetiming');
    }
}
