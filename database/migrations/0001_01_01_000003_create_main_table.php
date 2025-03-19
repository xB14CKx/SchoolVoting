<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 20)->nullable();
            $table->string('last_name', 20)->nullable();
            $table->enum('year_level', ['1st', '2nd', '3rd', '4th'])->nullable();
            $table->string('program', 30)->nullable();
            $table->binary('image')->nullable();
            $table->timestamps();
        });

        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->year('year')->default(date('Y'));
            $table->timestamps();
        });

        Schema::create('election_candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('candidate_id');
            $table->primary(['election_id', 'candidate_id']);
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('election_results', function (Blueprint $table) {
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('candidate_id');
            $table->integer('votes')->default(0);
            $table->primary(['election_id', 'candidate_id']);
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
        Schema::dropIfExists('election_results');
        Schema::dropIfExists('election_candidates');
        Schema::dropIfExists('elections');
        Schema::dropIfExists('candidates');
    }
};
