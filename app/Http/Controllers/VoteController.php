<?php

namespace App\Http\Controllers;

use App\Events\VoteCast;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
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

    public function store(Request $request, Election $election = null)
    {
        $user = Auth::user();

        // Check if the user is eligible to vote (e.g., role-based)
        if ($user->role !== 'voter') { // Adjust based on your role system
            return $this->respondWithError('You are not eligible to vote in this election.', $election);
        }

        // Check if the user has already voted
        if (Vote::where('user_id', $user->id)->where('election_id', $election?->id)->exists()) {
            return $this->respondWithError('You have already voted in this election.', $election);
        }

        // Handle single vote (original functionality)
        if ($election) {
            return $this->storeSingleVote($request, $election, $user);
        }

        // Handle multiple votes (new functionality)
        return $this->storeMultipleVotes($request, $user);
    }

    /**
     * Store a single vote for a specific election
     */
    protected function storeSingleVote(Request $request, Election $election, $user)
    {
        // Check if the election is open for voting
        if (!$this->isElectionOpen($election)) {
            return $this->respondWithError('This election is not currently open for voting.', $election);
        }

        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        // Ensure the candidate is part of this election
        if (!$election->candidates()->where('candidates.id', $validated['candidate_id'])->exists()) {
            return $this->respondWithError('Invalid candidate for this election.', $election);
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
            $positionId = $candidate->position_id;

            // Calculate updated vote counts
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
            return $this->respondWithError('Failed to record your vote: ' . $e->getMessage(), $election, 'votes.create');
        }
    }

    /**
     * Store multiple votes for different positions
     */
    protected function storeMultipleVotes(Request $request, $user)
    {
        $validated = $request->validate([
            'votes' => 'required|array',
            'votes.*' => 'required|exists:candidates,id'
        ]);

        try {
            foreach ($validated['votes'] as $position => $candidateId) {
                $candidate = Candidate::findOrFail($candidateId);
                Vote::create([
                    'user_id' => $user->id,
                    'candidate_id' => $candidateId,
                    'position' => $position,
                    'election_id' => $candidate->election_id // Assuming candidate has election_id
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Votes submitted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit votes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if the election is open for voting
     */
    protected function isElectionOpen(Election $election)
    {
        $now = now();
        return $election->status === 'open';
    }

    /**
     * Helper method to handle error responses
     */
    protected function respondWithError($message, ?Election $election = null, $route = 'elections.show')
    {
        if ($election) {
            return redirect()->route($route, $election)->with('error', $message);
        }
        return response()->json([
            'success' => false,
            'message' => $message
        ], 400);
    }
}
