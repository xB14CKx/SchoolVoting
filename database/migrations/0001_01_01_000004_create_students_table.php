<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key (replacing ID)
            $table->string('first_name'); // FIRST NAME
            $table->string('middle_initial')->nullable(); // M.I. (nullable since it might not always be provided)
            $table->string('last_name'); // LAST NAME
            $table->string('email')->unique(); // EMAIL (assuming emails should be unique)
            $table->string('program'); // PROGRAM
            $table->integer('year'); // YEAR
            $table->string('contact_number'); // CONTACT #
            $table->date('date_of_birth'); // DATE OF BIRTH
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}