<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('candidate', function (Blueprint $table) {
            $table->id('candidateID');
            $table->string('candidate_fname', 20)->nullable();
            $table->string('candidate_lname', 20)->nullable();
            $table->tinyInteger('candidate_year_level')->nullable();
            $table->string('candidate_program', 30)->nullable();
            $table->string('candidate_image', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('election', function (Blueprint $table) {
            $table->id('electionID');
            $table->year('year')->nullable();
            $table->timestamps();
        });

        Schema::create('election_candidate', function (Blueprint $table) {
            $table->unsignedBigInteger('electionID');
            $table->unsignedBigInteger('candidateID');
            $table->primary(['electionID', 'candidateID']);
            $table->foreign('electionID')->references('electionID')->on('election')->onDelete('cascade');
            $table->foreign('candidateID')->references('candidateID')->on('candidate')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('election_results', function (Blueprint $table) {
            $table->unsignedBigInteger('electionID');
            $table->unsignedBigInteger('candidateID');
            $table->integer('votes')->nullable();
            $table->primary(['electionID', 'candidateID']);
            $table->foreign('electionID')->references('electionID')->on('election')->onDelete('cascade');
            $table->foreign('candidateID')->references('candidateID')->on('candidate')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('election_results');
        Schema::dropIfExists('election_candidate');
        Schema::dropIfExists('election');
        Schema::dropIfExists('candidate');
    }
};
