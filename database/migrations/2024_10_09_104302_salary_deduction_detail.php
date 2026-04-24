<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SalaryDeductionDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_deduction_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salary_id');
            $table->foreign('salary_id')->references('id')->on('salary_counts');
            $table->boolean('deduction_type')->nullable(); 
            $table->decimal('deduction_amount', 10, 2)->default(0);
            $table->string('deduction_reason')->nullable(); 
            $table->string('month', 7);
         
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
        Schema::dropIfExists('salary_deduction_detail');
    }
}
