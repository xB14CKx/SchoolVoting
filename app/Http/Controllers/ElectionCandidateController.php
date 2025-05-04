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
        $election = ElectionController::getOrCreateCurrentElection();
        $candidates = Candidate::all();
        return view('election_candidates.create', compact('election', 'candidates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_id' => 'required|exists:elections,election_id',
            'candidate_id' => 'required|exists:candidates,candidate_id',
        ]);

        try {
            $election = Election::where('election_id', $validated['election_id'])->firstOrFail();

            if ($election->candidates()->where('candidates.candidate_id', $validated['candidate_id'])->exists()) {
                return redirect()->route('elections.candidates.create', ['election' => $election->election_id])
                    ->with('error', 'This candidate is already added to the election.');
            }

            $election->candidates()->attach($validated['candidate_id']);

            return redirect()->route('elections.candidates.create', ['election' => $election->election_id])
                ->with('success', 'Candidate added to election.');
        } catch (\Exception $e) {
            return redirect()->route('elections.candidates.create')
                ->with('error', 'Failed to add candidate to election: ' . $e->getMessage());
        }
    }

    public function destroy(Election $election, Candidate $candidate)
    {
        try {
            $election->candidates()->detach($candidate->candidate_id);

            return redirect()->route('elections.candidates.create', ['election' => $election->election_id])
                ->with('success', 'Candidate removed from election.');
        } catch (\Exception $e) {
            return redirect()->route('elections.candidates.create', ['election' => $election->election_id])
                ->with('error', 'Failed to remove candidate from election: ' . $e->getMessage());
        }
    }

    public function elect()
    {
        $currentElection = Election::where('status', 'open')->latest()->first();

        $positions = \App\Models\Position::with(['candidates' => function ($query) use ($currentElection) {
            $query->whereHas('elections', function ($q) use ($currentElection) {
                $q->where('election_id', $currentElection->election_id);
            })->with(['program', 'partylist']);
        }])->get();

        return view('votings.elect', compact('positions'));
    }
}
