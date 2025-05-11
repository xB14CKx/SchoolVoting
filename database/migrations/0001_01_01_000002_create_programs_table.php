<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Class CreateProgramsTable extends Migration {
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->bigIncrements('program_id');
            $table->string('program_name', 100)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
};
