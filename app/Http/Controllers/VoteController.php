<?php

namespace App\Http\Controllers;

use App\Events\VoteCast;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    // No need for constructor since middleware is applied in web.php
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function create(Election $election)
    {
        // Check if the election is open for voting
        if (!$this->isElectionOpen($election)) {
            return redirect()->route('elections.show', $election)
                ->with('error', 'This election is not currently open for voting.');
        }

        $candidates = $election->candidates;
        return view('votes.create', compact('election', 'candidates'));
    }

    public function store(Request $request, Election $election)
    {
        $user = auth()->user();

        // Check if the user is eligible to vote (e.g., role-based)
        if ($user->role !== 'voter') { // Adjust based on your role system
            return redirect()->route('elections.show', $election)
                ->with('error', 'You are not eligible to vote in this election.');
        }

        // Check if the election is open for voting
        if (!$this->isElectionOpen($election)) {
            return redirect()->route('elections.show', $election)
                ->with('error', 'This election is not currently open for voting.');
        }

        // Check if the user has already voted in this election
        if ($user->votes()->where('election_id', $election->id)->exists()) {
            return redirect()->route('elections.show', $election)
                ->with('error', 'You have already voted in this election.');
        }

        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        // Ensure the candidate is part of this election
        if (!$election->candidates()->where('candidates.id', $validated['candidate_id'])->exists()) {
            return redirect()->route('elections.show', $election)
                ->with('error', 'Invalid candidate for this election.');
        }

        try {
            // Record the vote
            $vote = Vote::create([
                'election_id' => $election->id,
                'candidate_id' => $validated['candidate_id'],
                'user_id' => $user->id,
            ]);

            // Get the candidate and their position
            $candidate = Candidate::findOrFail($validated['candidate_id']);
            $positionId = $candidate->position_id; // Assumes a position_id field on the candidates table

            // Calculate updated vote counts for all candidates in this position
            $candidates = Candidate::where('position_id', $positionId)
                ->withCount('votes')
                ->get();

            $candidateVotes = $candidates->mapWithKeys(function ($candidate) {
                return [$candidate->id => $candidate->votes_count];
            })->toArray();

            // Broadcast the updated vote counts
            event(new VoteCast($positionId, $candidateVotes));

            return redirect()->route('elections.show', $election)
                ->with('success', 'Your vote has been recorded.');
        } catch (\Exception $e) {
            return redirect()->route('votes.create', $election)
                ->with('error', 'Failed to record your vote: ' . $e->getMessage());
        }
    }

    /**
     * Check if the election is open for voting.
     *
     * @param Election $election
     * @return bool
     */
    protected function isElectionOpen(Election $election)
    {
        // Adjust this logic based on your Election model (e.g., start_date and end_date fields)
        $now = now();
        return $election->status === 'open'; // Example: Check if status is 'open'
        // Alternatively, if you have start_date and end_date:
        // return $now->between($election->start_date, $election->end_date);
    }
}