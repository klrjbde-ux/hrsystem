<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Employeebreak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employeebreak', function (Blueprint $table) {  
            $table->increments('id');
            $table->integer('employee_id');
            $table->time('break_start_time')->nullable();
            $table->time('break_end_time')->nullable();
            $table->string('total_time')->nullable();
            $table->date('date');
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
        Schema::dropIfExists('employeebreak');
    }
}
