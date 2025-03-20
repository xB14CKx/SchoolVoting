<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;

class ElectionCandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        // Attach the candidate to the election
        $election->candidates()->syncWithoutDetaching($validated['candidate_id']);

        return redirect()->route('elections.show', $election)->with('success', 'Candidate added to election.');
    }

    public function destroy(Election $election, Candidate $candidate)
    {
        // Detach the candidate from the election
        $election->candidates()->detach($candidate->id);

        return redirect()->route('elections.show', $election)->with('success', 'Candidate removed from election.');
    }
}