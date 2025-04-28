<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Http\Controllers\ElectionController;

class ElectionCandidateController extends Controller
{
    public function create(Request $request)
    {
        // Find or create the current year's election
        $election = ElectionController::getOrCreateCurrentElection();
        $candidates = Candidate::all();
        return view('election_candidates.create', compact('election', 'candidates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_id' => 'required|exists:elections,id',
            'candidate_id' => 'required|exists:candidates,candidate_id',
        ]);

        try {
            $election = Election::findOrFail($validated['election_id']);

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
            return redirect()->route('election_candidates.create')
                ->with('error', 'Failed to add candidate to election: ' . $e->getMessage());
        }
    }

    public function destroy(Election $election, Candidate $candidate)
    {
        try {
            // Detach the candidate from the election
            $election->candidates()->detach($candidate->candidate_id);

            return redirect()->route('elections.show', $election)
                ->with('success', 'Candidate removed from election.');
        } catch (\Exception $e) {
            return redirect()->route('elections.show', $election)
                ->with('error', 'Failed to remove candidate from election: ' . $e->getMessage());
        }
    }

    public function elect()
    {
        $currentElection = \App\Models\Election::where('status', 'open')->latest()->first();
    
        $positions = \App\Models\Position::with(['candidates' => function ($query) use ($currentElection) {
            $query->whereHas('elections', function ($q) use ($currentElection) {
                $q->where('election_id', $currentElection->election_id);
            })->with(['program', 'partylist']);
        }])->get();
    
        return view('votings.elect', compact('positions'));
    }
}
