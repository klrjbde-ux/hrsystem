<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('employee_id');
            $table->time('first_time_in')->nullable();
            $table->time('last_time_out')->nullable();
            $table->integer('total_time');
            $table->date('date');
            $table->string('status')->default('present');
            $table->time('is_delay')->nullable();
            $table->time('extra_time')->nullable();
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
        Schema::dropIfExists('attendance');
    }
}
