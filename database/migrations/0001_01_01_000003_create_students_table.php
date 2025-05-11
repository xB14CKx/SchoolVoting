<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->primary(); // Set student_id as primary key
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('email', 255)->unique();
            $table->foreignId('program_id')
                ->nullable()
                ->constrained('programs', 'program_id')
                ->onDelete('set null');
            $table->enum('year_level', ['1st', '2nd', '3rd', '4th']);
            $table->string('contact_number', 255);
            $table->string('image', 255)->nullable();
            $table->date('date_of_birth');
            $table->enum('sex', ['Male', 'Female']);
            $table->timestamps();
            $table->index('year_level');
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
