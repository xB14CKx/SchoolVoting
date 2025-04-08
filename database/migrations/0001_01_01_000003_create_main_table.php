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
                'PIO',
                'Business Manager'
            ])->unique();
            $table->timestamps();

            // Add table comment
            $table->comment = 'Stores available positions for elections';
        });

        // Programs table (based on the second image)
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // varchar(255) for program name
            $table->timestamps();

            // Add table comment (optional, inferred)
            $table->comment = 'Stores academic programs';
        });

        // Partylists table (based on the first image)
        Schema::create('partylists', function (Blueprint $table) {
            $table->id('partylist_id'); // int(11), auto_increment
            $table->string('partylist_name', 255)->collation('utf8mb4_general_ci'); // varchar(255)
            $table->string('election_year', 9)->collation('utf8mb4_general_ci'); // varchar(9)
            $table->timestamps();

            // Add table comment (optional, inferred)
            $table->comment = 'Stores partylist information for elections';
        });

        // Candidates table (already matches the third image)
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained()->onDelete('restrict'); // Link to positions table
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->string('middle_name', 20)->nullable(); // Added based on the third image
            $table->enum('year_level', ['1st', '2nd', '3rd', '4th']);
            $table->foreignId('program_id')->constrained()->onDelete('restrict'); // Link to programs table
            $table->foreignId('partylist_id')->constrained()->onDelete('restrict'); // Link to partylists table
            $table->string('image', 255)->nullable(); // varchar(255), nullable
            $table->timestamps();

            // Optional: Add unique constraint on first_name, last_name, and position_id
            $table->unique(['first_name', 'last_name', 'position_id'], 'candidates_name_position_unique');

            // Add indexes for faster queries
            $table->index('program_id');
            $table->index('year_level');
            $table->index('position_id');
            $table->index('partylist_id');

            // Add table comment
            $table->comment = 'Stores candidate information for elections';
        });

        // Elections table
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->enum('status', ['pending', 'open', 'closed'])->default('pending'); // Added to track election state
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
        Schema::dropIfExists('partylists'); // Added to drop the new table
        Schema::dropIfExists('programs'); // Added to drop the new table
        Schema::dropIfExists('positions');
    }
};
