<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Positions table
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->enum('name', [
                'President',
                'Vice President',
                'Secretary',
                'Treasurer',
                'Auditor',
                'Student PIO',
                'Business Manager'
            ])->unique();
            $table->timestamps();

            // Add table comment
            $table->comment = 'Stores available positions for elections';
        });

        // Candidates table
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained()->onDelete('restrict'); // Link to positions table
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->string('middle_name', 20)->nullable();
            $table->enum('year_level', ['1st', '2nd', '3rd', '4th']);
            $table->foreignId('program_id')->constrained()->onDelete('restrict');
            $table->foreignId('partylist_id')->constrained()->onDelete('restrict');
            $table->string('image')->nullable(); // Changed from binary to string to store file path
            $table->timestamps();

            // Optional: Add unique constraint on first_name, last_name, and position_id
            $table->unique(['first_name', 'last_name', 'position_id'], 'candidates_name_position_unique');

            // Add indexes for faster queries
            $table->index('year_level');
            $table->index('position_id');
            $table->index('program_id');
            $table->index('partylist_id');

            // Add table comment
            $table->comment = 'Stores candidate information for elections';
        });

        // Elections table
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->enum('status', ['pending', 'open', 'closed'])->default('pending'); // Added to track election state
            // Alternatively, if using dates:
            // $table->dateTime('start_date')->nullable();
            // $table->dateTime('end_date')->nullable();
            $table->timestamps();

            // Add unique constraint on year
            $table->unique('year');

            // Add table comment
            $table->comment = 'Stores election information';
        });

        // Election Candidates pivot table
        Schema::create('election_candidates', function (Blueprint $table) {
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->primary(['election_id', 'candidate_id']);
            $table->timestamps();

            // Add table comment
            $table->comment = 'Pivot table linking elections to candidates';
        });

        // Election Results table
        Schema::create('election_results', function (Blueprint $table) {
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('votes')->default(0);
            $table->primary(['election_id', 'candidate_id']);
            $table->timestamps();

            // Add index on votes for faster sorting
            $table->index('votes');

            // Add table comment
            $table->comment = 'Stores vote tallies for candidates in elections';
        });

        // Votes table
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained()->onDelete('cascade'); // Added to track position
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Add unique constraint to prevent duplicate votes per position in an election
            $table->unique(['election_id', 'user_id', 'position_id'], 'votes_election_user_position_unique');

            // Add index for faster vote tallying
            $table->index(['election_id', 'candidate_id'], 'votes_election_candidate_index');
            $table->index('position_id');

            // Add table comment
            $table->comment = 'Stores individual votes cast by users';
        });
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key constraint issues
        Schema::dropIfExists('votes');
        Schema::dropIfExists('election_results');
        Schema::dropIfExists('election_candidates');
        Schema::dropIfExists('elections');
        Schema::dropIfExists('candidates');
        Schema::dropIfExists('positions'); // Added to drop the new table
    }
};