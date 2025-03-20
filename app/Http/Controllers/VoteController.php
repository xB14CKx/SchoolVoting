<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Election $election)
    {
        $candidates = $election->candidates;
        return view('votes.create', compact('election', 'candidates'));
    }

    public function store(Request $request, Election $election)
    {
        $user = auth()->user();

        // Check if the user has already voted in this election
        if ($user->votes()->where('election_id', $election->id)->exists()) {
            return redirect()->route('elections.show', $election)->with('error', 'You have already voted in this election.');
        }

        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        // Ensure the candidate is part of this election
        if (!$election->candidates()->where('candidates.id', $validated['candidate_id'])->exists()) {
            return redirect()->route('elections.show', $election)->with('error', 'Invalid candidate for this election.');
        }

        // Record the vote
        Vote::create([
            'election_id' => $election->id,
            'candidate_id' => $validated['candidate_id'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('elections.show', $election)->with('success', 'Your vote has been recorded.');
    }
}