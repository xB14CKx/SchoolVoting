<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Programs table
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        // Partylists table
        Schema::create('partylists', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        // Positions table
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->enum('name', [
                'President',
                'Vice President',
                'Secretary',
                'Treasurer',
                'Auditor',
                'PIO',
                'Business Manager'
            ])->unique();
            $table->timestamps();
        });

        // Candidates table
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained()->onDelete('restrict');
            $table->foreignId('program_id')->constrained()->onDelete('restrict');
            $table->foreignId('partylist_id')->nullable()->constrained()->onDelete('set null');
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->enum('year_level', ['1st', '2nd', '3rd', '4th']);
            $table->string('image')->nullable();
            $table->text('platform')->nullable();
            $table->string('program', 100)->nullable();
            $table->timestamps();

            $table->unique(['first_name', 'last_name', 'position_id'], 'candidates_name_position_unique');
            $table->index('program_id');
            $table->index('partylist_id');
            $table->index('year_level');
            $table->index('position_id');
        });

        // Elections table
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->enum('status', ['pending', 'open', 'closed'])->default('pending');
            $table->timestamps();
            $table->unique('year');
        });

        // Election Candidates pivot table
        Schema::create('election_candidates', function (Blueprint $table) {
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->primary(['election_id', 'candidate_id']);
            $table->timestamps();
        });

        // Election Results table
        Schema::create('election_results', function (Blueprint $table) {
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('votes')->default(0);
            $table->primary(['election_id', 'candidate_id']);
            $table->timestamps();
            $table->index('votes');
        });

        // Votes table
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('position', 50)->nullable();
            $table->timestamps();

            $table->unique(['election_id', 'user_id', 'position'], 'votes_election_user_position_unique');
            $table->index(['election_id', 'candidate_id'], 'votes_election_candidate_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
        Schema::dropIfExists('election_results');
        Schema::dropIfExists('election_candidates');
        Schema::dropIfExists('elections');
        Schema::dropIfExists('candidates');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('partylists');
        Schema::dropIfExists('programs');
    }
};
