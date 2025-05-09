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
use Illuminate\Support\Facades\Log;

class VoteController extends Controller
{
    public function elect()
    {
        if (!Auth::check() || !Auth::user()->isStudent()) {
            Log::warning('Non-student user attempted to access elect', [
                'user_id' => Auth::id(),
                'role' => Auth::user()->role ?? 'none',
            ]);
            abort(403, 'Unauthorized: Only students can access this page.');
        }

        $currentYear = date('Y');

        $election = Election::firstOrCreate(
            ['year' => $currentYear],
            ['year' => $currentYear, 'status' => 'open']
        );

        if (!$election->exists || !$election->election_id) {
            Log::error('Election not found or invalid', [
                'year' => $currentYear,
                'election' => $election->toArray(),
            ]);
            return redirect()->route('elections.index')->with('error', 'Unable to create or find an election for the current year.');
        }

        $isElectionOpen = $this->isElectionOpen($election);

        $positions = Position::with(['candidates' => function ($query) use ($election) {
            $query->whereHas('elections', function ($q) use ($election) {
                $q->where('elections.election_id', $election->election_id);
            })->with(['program', 'partylist']);
        }])->get();

        if ($positions->pluck('candidates')->flatten()->isEmpty()) {
            Log::warning('No candidates found for election', [
                'election_id' => $election->election_id,
                'positions' => $positions->pluck('position_id')->toArray(),
            ]);
        }

        return view('votings.elect', compact('positions', 'election', 'isElectionOpen'));
    }

    public function create(Election $election)
    {
        if (!Auth::check() || !Auth::user()->isStudent()) {
            Log::warning('Non-student user attempted to access vote create', [
                'user_id' => Auth::id(),
                'role' => Auth::user()->role ?? 'none',
            ]);
            abort(403, 'Unauthorized: Only students can vote.');
        }

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

        if (!Auth::check() || !Auth::user()->isStudent()) {
            Log::warning('Non-student user attempted to vote', [
                'user_id' => Auth::id(),
                'role' => Auth::user()->role ?? 'none',
            ]);
            return $this->respondWithError('You are not eligible to vote.', $election);
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

        if (!$election->candidates()->where('candidate_id', $validated['candidate_id'])->exists()) {
            return $this->respondWithError('Invalid candidate for this election.', $election);
        }

        try {
            $candidate = Candidate::findOrFail($validated['candidate_id']);
            $vote = Vote::create([
                'election_id' => $election->election_id,
                'candidate_id' => $validated['candidate_id'],
                'user_id' => $user->id,
                'position_id' => $candidate->position_id,
            ]);

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
            Log::error('Failed to record single vote: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'election_id' => $election->election_id,
                'candidate_id' => $validated['candidate_id'],
            ]);
            return $this->respondWithError('Failed to record your vote: ' . $e->getMessage(), $election, 'votes.create');
        }
    }

    protected function storeMultipleVotes(Request $request, $user)
    {
        Log::info('Vote submission raw input:', $request->all());
        Log::info('User ID:', ['user_id' => $user->id]);

        $validated = $request->validate([
            'election_id' => 'required|exists:elections,election_id',
            'votes' => 'required|array',
            'votes.*' => [
                'required',
                'exists:candidates,candidate_id',
                function ($attribute, $value, $fail) use ($request) {
                    $positionId = explode('.', $attribute)[1];
                    $candidate = Candidate::find($value);
                    if (!$candidate) {
                        return $fail("Candidate ID {$value} does not exist.");
                    }
                    if ($candidate->position_id != $positionId) {
                        return $fail("Candidate ID {$value} does not belong to position ID {$positionId}.");
                    }
                    $electionId = $request->input('election_id');
                    if (!$candidate->elections()->where('elections.election_id', $electionId)->exists()) {
                        return $fail("Candidate ID {$value} does not belong to this election.");
                    }
                },
            ],
        ]);

        $election = Election::findOrFail($validated['election_id']);
        if (!$this->isElectionOpen($election)) {
            return $this->respondWithError('This election is not open.');
        }

        $existingVotes = Vote::where('user_id', $user->id)
                            ->where('election_id', $election->election_id)
                            ->pluck('position_id')
                            ->toArray();

        $newPositionIds = array_keys($validated['votes']);
        $duplicatePositions = array_intersect($existingVotes, $newPositionIds);

        if (!empty($duplicatePositions)) {
            return $this->respondWithError('You have already voted for the following positions: ' . implode(', ', $duplicatePositions) . '.');
        }

        try {
            DB::beginTransaction();

            $positionIds = array_keys($validated['votes']);
            foreach ($positionIds as $positionId) {
                $candidateId = $validated['votes'][$positionId];
                Vote::create([
                    'user_id' => $user->id,
                    'candidate_id' => $candidateId,
                    'position_id' => $positionId,
                    'election_id' => $election->election_id,
                ]);
            }

            foreach ($positionIds as $positionId) {
                $candidates = Candidate::where('position_id', $positionId)
                    ->withCount('votes')
                    ->get();

                $candidateVotes = $candidates->mapWithKeys(function ($candidate) {
                    return [$candidate->candidate_id => $candidate->votes_count];
                })->toArray();

                Log::info('Dispatching VoteCast event', [
                    'position_id' => $positionId,
                    'candidate_votes' => $candidateVotes,
                ]);
                event(new VoteCast($positionId, $candidateVotes));
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Votes submitted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to submit votes: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'election_id' => $validated['election_id'],
                'user_id' => $user->id,
            ]);
            return response()->json(['success' => false, 'message' => 'Error submitting votes: ' . $e->getMessage()], 400);
        }
    }

    public function voteCountingPage()
    {
        $currentYear = date('Y');
        $election = Election::where('year', $currentYear)->first();
        if (!$election) {
            return redirect()->back()->with('error', 'No election found for the current year.');
        }

        $positions = Position::with(['candidates' => function ($query) use ($election) {
            $query->whereHas('elections', function ($q) use ($election) {
                $q->where('elections.election_id', $election->election_id);
            })
            ->with(['program', 'partylist'])
            ->withCount(['votes' => function ($q) use ($election) {
                $q->where('election_id', $election->election_id);
            }]);
        }])->get();

        $positionsData = $positions->map(function ($position) {
            return [
                'position_id' => $position->position_id,
                'position_name' => $position->position_name,
                'candidates' => $position->candidates->map(function ($candidate) {
                    return [
                        'candidate_id' => $candidate->candidate_id,
                        'first_name' => $candidate->first_name,
                        'middle_name' => $candidate->middle_name,
                        'last_name' => $candidate->last_name,
                        'full_name' => $candidate->last_name . ', ' . $candidate->first_name . ($candidate->middle_name ? ' ' . $candidate->middle_name : ''),
                        'program' => $candidate->program->program_name ?? '',
                        'partylist' => $candidate->partylist->partylist_name ?? '',
                        'year_level' => $candidate->year_level,
                        'platform' => $candidate->platform,
                        'image' => $candidate->image ? asset('storage/' . $candidate->image) : asset('images/default-candidate.png'),
                        'votes_count' => $candidate->votes_count,
                    ];
                }),
            ];
        });

        if ($positionsData->isEmpty()) {
            $positionsData = collect();
        }

        return view('votings.vote-counting', [
            'positions' => $positionsData,
            'election' => $election,
        ]);
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
