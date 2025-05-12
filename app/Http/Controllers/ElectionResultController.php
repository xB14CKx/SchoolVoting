<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionResult;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ElectionResultController extends Controller
{
    public function show(Election $election)
    {
        // Fetch all positions, fallback to empty collection if none
        $positions = Position::all() ?: collect();

        // Get the selected position_id from the query parameter, default to the first position if available
        $selectedPositionId = request()->query('position_id', $positions->first()->id ?? null);

        // Fetch results for the election, filtered by the selected position_id
        $results = ElectionResult::where('election_id', $election->id)
            ->when($selectedPositionId, function ($query) use ($selectedPositionId) {
                return $query->where('position_id', $selectedPositionId);
            })
            ->with('candidate')
            ->get();

        // Define a default position name
        $defaultPositionName = $selectedPositionId ? Position::find($selectedPositionId)->name ?? 'President' : 'President';

        return view('election_results.show', compact('election', 'results', 'positions', 'defaultPositionName', 'selectedPositionId'));
    }

    public function update(Request $request, Election $election)
    {
        $this->middleware('admin')->only('update');

        if ($election->status !== 'closed') {
            return redirect()->route('election_results.show', $election)
                ->with('error', 'Election results can only be updated after the election is closed.')
                ->with('results', ElectionResult::where('election_id', $election->id)->with('candidate')->get())
                ->with('positions', Position::all() ?: collect())
                ->with('defaultPositionName', 'President');
        }

        try {
            ElectionResult::where('election_id', $election->id)->delete();
            $votes = $election->votes()
                ->selectRaw('candidate_id, position_id, COUNT(*) as vote_count')
                ->groupBy('candidate_id', 'position_id')
                ->get();

            foreach ($votes as $vote) {
                ElectionResult::create([
                    'election_id' => $election->id,
                    'candidate_id' => $vote->candidate_id,
                    'position_id' => $vote->position_id,
                    'votes' => $vote->vote_count,
                ]);
            }

            $positions = Position::all() ?: collect();
            $selectedPositionId = request()->query('position_id', $positions->first()->id ?? null);
            $results = ElectionResult::where('election_id', $election->id)
                ->when($selectedPositionId, function ($query) use ($selectedPositionId) {
                    return $query->where('position_id', $selectedPositionId);
                })
                ->with('candidate')
                ->get();
            $defaultPositionName = $selectedPositionId ? Position::find($selectedPositionId)->name ?? 'President' : 'President';

            return view('election_results.show', compact('election', 'results', 'positions', 'defaultPositionName', 'selectedPositionId'))
                ->with('success', 'Election results updated.');
        } catch (\Exception $e) {
            return redirect()->route('election_results.show', $election)
                ->with('error', 'Failed to update election results: ' . $e->getMessage())
                ->with('results', ElectionResult::where('election_id', $election->id)->with('candidate')->get())
                ->with('positions', Position::all() ?: collect())
                ->with('defaultPositionName', 'President');
        }
    }

    public function closeElection(Request $request, Election $election)
    {
        $this->middleware('admin')->only('closeElection');

        if ($election->status === 'closed') {
            return redirect()->route('election_results.show', $election)
                ->with('error', 'Election is already closed.')
                ->with('results', ElectionResult::where('election_id', $election->id)->with('candidate')->get())
                ->with('positions', Position::all() ?: collect())
                ->with('defaultPositionName', 'President');
        }

        try {
            $election->update(['status' => 'closed']);
            $votes = $election->votes()
                ->selectRaw('candidate_id, position_id, COUNT(*) as vote_count')
                ->groupBy('candidate_id', 'position_id')
                ->get();

            ElectionResult::where('election_id', $election->id)->delete();

            foreach ($votes as $vote) {
                ElectionResult::create([
                    'election_id' => $election->id,
                    'candidate_id' => $vote->candidate_id,
                    'position_id' => $vote->position_id,
                    'votes' => $vote->vote_count,
                ]);
            }

            // Determine winner (highest vote count per position)
            $positionIds = $votes->pluck('position_id')->unique();
            foreach ($positionIds as $positionId) {
                $winnerResult = ElectionResult::where('election_id', $election->id)
                    ->where('position_id', $positionId)
                    ->orderBy('votes', 'desc')
                    ->first();
                if ($winnerResult) {
                    Log::info('Winner determined', ['election_id' => $election->id, 'position_id' => $positionId, 'candidate_id' => $winnerResult->candidate_id, 'votes' => $winnerResult->votes]);
                }
            }

            $positions = Position::all() ?: collect();
            $selectedPositionId = request()->query('position_id', $positions->first()->id ?? null);
            $results = ElectionResult::where('election_id', $election->id)
                ->when($selectedPositionId, function ($query) use ($selectedPositionId) {
                    return $query->where('position_id', $selectedPositionId);
                })
                ->with('candidate')
                ->get();
            $defaultPositionName = $selectedPositionId ? Position::find($selectedPositionId)->name ?? 'President' : 'President';

            return view('election_results.show', compact('election', 'results', 'positions', 'defaultPositionName', 'selectedPositionId'))
                ->with('success', 'Election closed and winners determined.');
        } catch (\Exception $e) {
            Log::error('Failed to close election', [
                'election_id' => $election->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('election_results.show', $election)
                ->with('error', 'Failed to close election: ' . $e->getMessage())
                ->with('results', ElectionResult::where('election_id', $election->id)->with('candidate')->get())
                ->with('positions', Position::all() ?: collect())
                ->with('defaultPositionName', 'President');
        }
    }
}
