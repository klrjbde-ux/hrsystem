<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_counts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->foreign('employee_id')->references('id')->on('employees');
                $table->string('month', 7);
                $table->decimal('gross_salary', 10, 2)->nullable();
                $table->decimal('bonus', 10, 2)->default(0);
                $table->decimal('deduction', 10, 2)->default(0);
                $table->integer('total_leaves')->default(0);               
                 $table->decimal('payable_salary', 10, 2)->default(0);
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
                Schema::dropIfExists('salary_counts');
            }
}
