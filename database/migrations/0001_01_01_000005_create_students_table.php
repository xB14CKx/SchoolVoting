<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigInteger('id')->primary(); // Non-auto-incrementing primary key
            $table->string('first_name', 50); // FIRST NAME
            $table->string('middle_name', 50)->nullable(); // MIDDLE NAME (nullable)
            $table->string('last_name', 50); // LAST NAME
            $table->string('email', 255)->unique(); // EMAIL (unique)
            $table->foreignId('program_id')->nullable()->constrained('programs', 'program_id')->onDelete('set null'); // PROGRAM (foreign key, nullable)
            $table->enum('year_level', ['1st', '2nd', '3rd', '4th']); // YEAR LEVEL (ENUM)
            $table->string('contact_number', 255); // CONTACT NUMBER
            $table->date('date_of_birth'); // DATE OF BIRTH
            $table->timestamps(); // created_at and updated_at

            // Adding foreign key constraint index
            $table->index('program_id'); // Index for program_id
            $table->index('year_level'); // Index for year_level
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
