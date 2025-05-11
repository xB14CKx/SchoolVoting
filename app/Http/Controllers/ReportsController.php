<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
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

        return view('votings.reports', [
            'positions' => $positionsData,
            'election' => $election,
        ]);
    }
}
