<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionResult;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionResultController extends Controller
{
    public function show(Request $request, Election $election)
    {
        // Fetch all results for the election with detailed candidate data
        $results = ElectionResult::where('election_id', $election->election_id)
            ->with([
                'candidate' => function ($query) {
                    $query->with(['position', 'program', 'partylist']);
                },
                'candidate.position',
                'candidate.program',
                'candidate.partylist'
            ])
            ->get();

        // Group results by position and find the winner for each
        $winnersByPosition = [];
        $positions = $results->groupBy('candidate.position.name');

        foreach ($positions as $positionName => $positionResults) {
            $winner = $positionResults->sortByDesc('votes')->first();
            if ($winner) {
                $winnersByPosition[$positionName] = [
                    'candidate' => $winner->candidate,
                    'votes' => $winner->votes,
                    'totalVotes' => $positionResults->sum('votes'),
                ];
            }
        }

        // Calculate total votes across all positions for overall statistics
        $totalVotes = $results->sum('votes');

        // Debug: Check if $winnersByPosition is populated
        if (empty($winnersByPosition)) {
            \Log::info('No winners found for election ID: ' . $election->election_id);
        }

        // Render the votings.result view
        return view('votings.result', compact('election', 'winnersByPosition', 'totalVotes'));
    }

    public function update(Request $request, Election $election)
    {
        try {
            // Validate that the election is closed
            if ($election->status !== 'Closed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Election results can only be updated after the election is closed.'
                ], 400);
            }

            // Clear old results for this election
            ElectionResult::where('election_id', $election->election_id)->delete();

            // Recalculate votes based on the votes table
            $votes = $election->votes()
                ->selectRaw('candidate_id, COUNT(*) as vote_count')
                ->groupBy('candidate_id')
                ->get();

            // Store results
            foreach ($votes as $vote) {
                ElectionResult::create([
                    'election_id' => $election->election_id,
                    'candidate_id' => $vote->candidate_id,
                    'votes' => $vote->vote_count,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Election results updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update election results: ' . $e->getMessage()
            ], 500);
        }
    }
}
