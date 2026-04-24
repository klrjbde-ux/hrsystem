<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appraisals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('performance_review_id');
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->decimal('rating', 4, 2)->nullable();
            $table->string('rating_scale')->nullable(); // 1-5, 1-10, etc.
            $table->text('comments')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('status')->default('pending'); // pending, completed, acknowledged
            $table->date('review_date')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('performance_review_id')->references('id')->on('performance_reviews')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};
