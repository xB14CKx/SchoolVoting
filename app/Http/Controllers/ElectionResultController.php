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
        $selectedPosition = $request->query('position', 'President');

        // Fetch results for the selected position
        $results = ElectionResult::where('election_id', $election->election_id)
            ->whereHas('candidate', function ($query) use ($selectedPosition) {
                $query->whereHas('position', function ($q) use ($selectedPosition) {
                    $q->where('name', $selectedPosition);
                });
            })
            ->with(['candidate', 'candidate.position', 'candidate.program', 'candidate.partylist'])
            ->get();

        // Calculate total votes for percentage
        $totalVotes = $results->sum('votes');

        // Calculate percentages and determine winner
        $results = $results->map(function ($result) use ($totalVotes) {
            $result->percentage = $totalVotes > 0 ? number_format(($result->votes / $totalVotes) * 100, 2) : 0;
            return $result;
        });

        // Find the winner (candidate with the most votes)
        $winner = $results->sortByDesc('votes')->first();

        return view('election_results.show', compact('election', 'results', 'winner', 'selectedPosition', 'totalVotes'));
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
