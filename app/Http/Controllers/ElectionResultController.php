<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionResult;
use Illuminate\Http\Request;

class ElectionResultController extends Controller
{
    public function show(Election $election)
    {
        $results = ElectionResult::where('election_id', $election->id)
            ->with('candidate')
            ->get();

        return view('election_results.show', compact('election', 'results'));
    }

    public function update(Request $request, Election $election)
    {
        // Restrict this action to admins (assumes 'admin' middleware exists)
        $this->middleware('admin')->only('update');

        // Validate that the election is in a state where results can be updated
        // For example, you might have a 'status' field on the Election model
        if ($election->status !== 'closed') { // Adjust 'status' and 'closed' based on your model
            return redirect()->route('election_results.show', $election)
                ->with('error', 'Election results can only be updated after the election is closed.');
        }

        try {
            // Clear old results for this election
            ElectionResult::where('election_id', $election->id)->delete();

            // Recalculate votes based on the votes table
            $votes = $election->votes()
                ->selectRaw('candidate_id, COUNT(*) as vote_count')
                ->groupBy('candidate_id')
                ->get();

            foreach ($votes as $vote) {
                ElectionResult::create([
                    'election_id' => $election->id,
                    'candidate_id' => $vote->candidate_id,
                    'votes' => $vote->vote_count,
                ]);
            }

            return redirect()->route('election_results.show', $election)
                ->with('success', 'Election results updated.');
        } catch (\Exception $e) {
            return redirect()->route('election_results.show', $election)
                ->with('error', 'Failed to update election results: ' . $e->getMessage());
        }
    }
}