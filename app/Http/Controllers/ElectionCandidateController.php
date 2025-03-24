<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;

class ElectionCandidateController extends Controller
{
    public function create(Election $election)
    {
        $candidates = Candidate::all();
        return view('election_candidates.create', compact('election', 'candidates'));
    }

    public function store(Request $request, Election $election)
    {
        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        try {
            // Check if the candidate is already attached to the election
            if ($election->candidates()->where('candidate_id', $validated['candidate_id'])->exists()) {
                return redirect()->route('elections.show', $election)
                    ->with('error', 'This candidate is already added to the election.');
            }

            // Attach the candidate to the election
            $election->candidates()->syncWithoutDetaching($validated['candidate_id']);

            return redirect()->route('elections.show', $election)
                ->with('success', 'Candidate added to election.');
        } catch (\Exception $e) {
            return redirect()->route('election_candidates.create', $election)
                ->with('error', 'Failed to add candidate to election: ' . $e->getMessage());
        }
    }

    public function destroy(Election $election, Candidate $candidate)
    {
        try {
            // Detach the candidate from the election
            $election->candidates()->detach($candidate->id);

            return redirect()->route('elections.show', $election)
                ->with('success', 'Candidate removed from election.');
        } catch (\Exception $e) {
            return redirect()->route('elections.show', $election)
                ->with('error', 'Failed to remove candidate from election: ' . $e->getMessage());
        }
    }
}