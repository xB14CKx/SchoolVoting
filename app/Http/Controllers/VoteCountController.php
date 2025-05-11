<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class VoteCountController extends Controller
{
    private function programAcronym($programName)
    {
        if (str_starts_with($programName, 'BS in ')) {
            $words = explode(' ', $programName);
            if (str_contains($programName, '–')) {
                [$mainProgram, $major] = array_map('trim', explode('–', $programName));
                $mainWords = array_filter(explode(' ', $mainProgram), fn($w) => strtolower($w) !== 'and');
                $initials = collect($mainWords)->slice(2)->map(fn($w) => $w[0])->implode('');
                if (str_contains($mainProgram, 'Entertainment and Multimedia Computing')) {
                    return 'BSEMC - ' . $major;
                } else {
                    return 'BS' . $initials . ' - ' . $major;
                }
            } else {
                $filteredWords = array_filter($words, fn($w) => strtolower($w) !== 'and');
                $initials = collect($filteredWords)->slice(2)->map(fn($w) => $w[0])->implode('');
                return 'BS' . $initials;
            }
        } elseif (str_starts_with($programName, 'Bachelor of Multimedia Arts')) {
            return 'BMA';
        } elseif (str_starts_with($programName, 'Bachelor of ')) {
            $acronym = str_replace('Bachelor of ', 'B', $programName);
            $words = array_filter(explode(' ', $acronym), fn($w) => strtolower($w) !== 'and');
            return collect($words)->map(fn($w) => $w[0])->implode('');
        }
        return $programName;
    }

    public function getDemographicBreakdown(Request $request)
    {
        $position = $request->input('position');
        $demographic = $request->input('demographic');

        $election = Election::orderByDesc('year')->first();
        if (!$election) {
            return response()->json(['error' => 'No election found'], 404);
        }

        $positionId = DB::table('positions')->where('position_name', $position)->value('position_id');
        if (!$positionId) {
            return response()->json(['error' => 'Invalid position'], 400);
        }

        // Get all possible demographic groups
        if ($demographic === 'program') {
            $programNames = DB::table('programs')->pluck('program_name')->toArray();
            $groups = array_map([$this, 'programAcronym'], $programNames);
        } elseif ($demographic === 'year_level') {
            $groups = ['1st', '2nd', '3rd', '4th'];
        } elseif ($demographic === 'gender' || $demographic === 'sex') {
            $groups = ['Male', 'Female'];
        } else {
            $groups = [];
        }

        // Get top 2 candidates for the position (even if they have zero votes)
        $candidates = Candidate::where('position_id', $positionId)
            ->whereHas('elections', function($q) use ($election) {
                $q->where('elections.election_id', $election->election_id);
            })
            ->withCount(['votes' => function($q) use ($election) {
                $q->where('election_id', $election->election_id);
            }])
            ->orderByDesc('votes_count')
            ->take(2)
            ->get();

        $result = [];
        foreach ($candidates as $candidate) {
            $votesQuery = Vote::where('candidate_id', $candidate->candidate_id)
                ->where('election_id', $election->election_id)
                ->join('users', 'votes.user_id', '=', 'users.id')
                ->join('students', 'students.id', '=', 'users.id');

            if ($demographic === 'program') {
                $votesQuery->leftJoin('programs', 'students.program_id', '=', 'programs.program_id')
                    ->select('programs.program_name as group', DB::raw('count(*) as count'))
                    ->groupBy('programs.program_name');
                $rawVotes = $votesQuery->pluck('count', 'group')->toArray();
                $votes = [];
                foreach ($rawVotes as $fullName => $count) {
                    $acronym = $this->programAcronym($fullName);
                    $votes[$acronym] = $count;
                }
            } elseif ($demographic === 'year_level') {
                $votesQuery->select('students.year_level as group', DB::raw('count(*) as count'))
                    ->groupBy('students.year_level');
                $votes = $votesQuery->pluck('count', 'group')->toArray();
            } elseif ($demographic === 'gender' || $demographic === 'sex') {
                $votesQuery->select('students.sex as group', DB::raw('count(*) as count'))
                    ->groupBy('students.sex');
                $votes = $votesQuery->pluck('count', 'group')->toArray();
            } else {
                $votesQuery->select('students.year_level as group', DB::raw('count(*) as count'))
                    ->groupBy('students.year_level');
                $votes = $votesQuery->pluck('count', 'group')->toArray();
            }

            // Ensure all groups are present, even if zero
            $allVotes = [];
            foreach ($groups as $group) {
                $allVotes[$group] = array_key_exists($group, $votes) ? $votes[$group] : 0;
            }

            $result[] = [
                'label' => $candidate->first_name . ' ' . $candidate->last_name,
                'values' => $allVotes,
            ];
        }

        // If there are less than 2 candidates, fill with empty data
        while (count($result) < 2) {
            $allVotes = [];
            foreach ($groups as $group) {
                $allVotes[$group] = 0;
            }
            $result[] = [
                'label' => '',
                'values' => $allVotes,
            ];
        }

        return response()->json([
            'candidate1' => $result[0],
            'candidate2' => $result[1],
        ]);
    }
}