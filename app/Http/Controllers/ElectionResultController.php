<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionResult;
use Illuminate\Http\Request;

class ElectionResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Election $election)
    {
        $results = ElectionResult::where('election_id', $election->id)
            ->with('candidate')
            ->get();

        return view('election_results.show', compact('election', 'results'));
    }

    public function update(Request $request, Election $election)
    {
        // Recalculate votes based on the votes table
        $votes = $election->votes()
            ->selectRaw('candidate_id, COUNT(*) as vote_count')
            ->groupBy('candidate_id')
            ->get();

        foreach ($votes as $vote) {
            ElectionResult::updateOrCreate(
                [
                    'election_id' => $election->id,
                    'candidate_id' => $vote->candidate_id,
                ],
                [
                    'votes' => $vote->vote_count,
                ]
            );
        }

        return redirect()->route('election_results.show', $election)->with('success', 'Election results updated.');
    }
}