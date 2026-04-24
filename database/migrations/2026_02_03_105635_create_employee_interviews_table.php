<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('employee_interviews', function (Blueprint $table) {
        $table->id();

        $table->string('candidate_name');
        $table->string('cv')->nullable(); // file path
        $table->decimal('current_salary', 10, 2)->nullable();
        $table->decimal('expected_salary', 10, 2)->nullable();

        $table->date('date_of_joining')->nullable();
        $table->date('interview_date');

        $table->enum('interview_status', ['Shortlisted', 'Hired', 'Rejected'])->default('Shortlisted');

        $table->text('interview_remarks')->nullable();
        $table->string('applied_for_job');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_interviews');
    }
};
