<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('personal_email')->unique();
            $table->string('gender');
            $table->date('dob');
            $table->string('emp_type');
            $table->string('emp_status');
            $table->string('designation');
            $table->string('department');
            $table->string('branch')->nullable();
            $table->date('joining_date');
            $table->string('manager')->nullable();
            $table->string('team')->nullable();
            $table->string('contact_no');
            $table->string('identity_no');
            $table->string('permanent_address');
            $table->string('current_address');
            $table->string('emergency_contact');
            $table->string('emergency_contact_address');
            $table->decimal('gross_salary', 20, 2)->nullable();
            $table->string('image')->nullable();

            // Foreign key to employee_contact_relations
            $table->unsignedBigInteger('relation_id')->nullable();
            $table->foreign('relation_id')
                  ->references('id')
                  ->on('employee_contact_relations')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop foreign key first
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['relation_id']);
        });

        // Drop the table
        Schema::dropIfExists('employees');
    }
}
