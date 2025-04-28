<?php

namespace App\Http\Controllers;

use App\Events\VoteCast;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  

class VoteController extends Controller
{
    public function elect()
    {

        $positions = Position::with('candidates')->get();
        return view('votings.elect', compact('positions'));

        $currentYear = date('Y');

        // Find or create an election for the current year with status 'open'
        $election = Election::firstOrCreate(
            ['year' => $currentYear],
            ['year' => $currentYear, 'status' => 'open']
        );

        // Ensure the election status is 'open'
        if ($election->status !== 'open') {
            $election->status = 'open';
            $election->save();
        }

        if (!$election->exists) {
            return redirect()->route('elections.index')->with('error', 'Unable to create or find an election for the current year.');
        }

        // Fetch candidates for this election
        $candidates = Candidate::select('candidates.*')
            ->join('election_candidates', 'candidates.candidate_id', '=', 'election_candidates.candidate_id')
            ->where('election_candidates.election_id', $election->id)
            ->with(['position', 'program', 'partylist'])
            ->get();

        return view('votings.elect', compact('candidates', 'election'));
    }

    public function create(Election $election)
    {
        if (!$this->isElectionOpen($election)) {
            return redirect()->route('elections.show', $election)
                ->with('error', 'This election is not currently open for voting.');
        }

        $candidates = $election->candidates()->with(['position', 'program', 'partylist'])->get();
        return view('votes.create', compact('election', 'candidates'));
    }

    public function store(Request $request, Election $election = null)
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            return $this->respondWithError('You are not eligible to vote.', $election);        }

        if ($election && Vote::where('user_id', $user->id)->where('election_id', $election->id)->exists()) {
            return $this->respondWithError('You have already voted in this election.', $election);
        }

        if ($election) {
            return $this->storeSingleVote($request, $election, $user);
        }

        return $this->storeMultipleVotes($request, $user);
    }

    protected function storeSingleVote(Request $request, Election $election, $user)
    {
        if (!$this->isElectionOpen($election)) {
            return $this->respondWithError('This election is not currently open for voting.', $election);
        }

        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,candidate_id',
        ]);

        if (!$election->candidates()->where('candidates.candidate_id', $validated['candidate_id'])->exists()) {
            return $this->respondWithError('Invalid candidate for this election.', $election);
        }

        try {
            $vote = Vote::create([
                'election_id' => $election->id,
                'candidate_id' => $validated['candidate_id'],
                'user_id' => $user->id,
            ]);

            $candidate = Candidate::findOrFail($validated['candidate_id']);
            $positionId = $candidate->position_id;

            $candidates = Candidate::where('position_id', $positionId)
                ->withCount('votes')
                ->get();

            $candidateVotes = $candidates->mapWithKeys(function ($candidate) {
                return [$candidate->candidate_id => $candidate->votes_count];
            })->toArray();

            event(new VoteCast($positionId, $candidateVotes));

            return redirect()->route('elections.show', $election)
                ->with('success', 'Your vote has been recorded.');
        } catch (\Exception $e) {
            return $this->respondWithError('Failed to record your vote: ' . $e->getMessage(), $election, 'votes.create');
        }
    }

    protected function storeMultipleVotes(Request $request, $user)
    {
        $validated = $request->validate([
            'election_id' => 'required|exists:elections,id',
            'votes' => 'required|array',
            'votes.*' => 'required|exists:candidates,candidate_id',
        ]);

        $election = Election::findOrFail($validated['election_id']);
        if (!$this->isElectionOpen($election)) {
            return $this->respondWithError('This election is not open.');
        }

        if (Vote::where('user_id', $user->id)->where('election_id', $election->id)->exists()) {
            return $this->respondWithError('You have already voted in this election.');
        }

        try {
            foreach ($validated['votes'] as $position => $candidateId) {
                $candidate = Candidate::findOrFail($candidateId);
                if (!$candidate->elections()->where('elections.id', $validated['election_id'])->exists()) {
                    throw new \Exception('Candidate does not belong to this election.');
                }
                Vote::create([
                    'user_id' => $user->id,
                    'candidate_id' => $candidateId,
                    'position' => $position,
                    'election_id' => $validated['election_id'],
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Votes submitted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to submit votes: ' . $e->getMessage()], 500);
        }
    }

    protected function isElectionOpen(Election $election)
    {
        return $election->year == date('Y') && $election->status === 'open';
    }

    protected function respondWithError($message, ?Election $election = null, $route = 'elections.show')
    {
        if ($election) {
            return redirect()->route($route, $election)->with('error', $message);
        }
        return response()->json(['success' => false, 'message' => $message], 400);
    }
}
