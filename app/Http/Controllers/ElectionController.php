<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Program;
use App\Models\Partylist;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ElectionController extends Controller
{
    /**
     * Helper method to find or create an election for the current year.
     *
     * @return \App\Models\Election
     * @throws \Exception
     */
    public static function getOrCreateCurrentElection()
    {
        $currentYear = date('Y');

        return DB::transaction(function () use ($currentYear) {
            $election = Election::where('year', $currentYear)
                ->lockForUpdate()
                ->first();

            if (!$election) {
                $election = Election::create([
                    'year' => $currentYear,
                    'status' => 'Pending',
                ]);
            }

            return $election;
        });
    }

    /**
     * Display the admin dashboard with election data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $programs = Program::all();
        $partylists = Partylist::all();
        $candidates = Candidate::with(['program', 'partylist', 'position'])->get();
        $currentElection = self::getOrCreateCurrentElection();

        return view('votings.admin', compact('programs', 'partylists', 'candidates', 'currentElection'));
    }

    public function create()
    {
        return view('elections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1) . '|unique:elections,year',
            'status' => ['required', Rule::in(['Pending', 'Open', 'Closed'])],
        ]);

        try {
            Election::create($validated);
            return redirect()->route('admin')->with('success', 'Election created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('elections.create')
                ->with('error', 'Failed to create election: ' . $e->getMessage());
        }
    }

    public function show(Election $election)
    {
        $election->load('candidates');
        return view('elections.show', compact('election'));
    }

    public function edit(Election $election)
    {
        return view('elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1) . '|unique:elections,year,' . $election->election_id . ',election_id',
            'status' => ['required', Rule::in(['Pending', 'Open', 'Closed'])],
        ]);

        try {
            $election->update($validated);
            return redirect()->route('admin')->with('success', 'Election updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('elections.edit', $election)
                ->with('error', 'Failed to update election: ' . $e->getMessage());
        }
    }

    public function destroy(Election $election)
    {
        try {
            $election->candidates()->detach();
            $election->delete();
            return redirect()->route('admin')->with('success', 'Election deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin')
                ->with('error', 'Failed to delete election: ' . $e->getMessage());
        }
    }

    public function open(Request $request, Election $election)
    {
        try {
            if ($election->status === 'Open') {
                return response()->json([
                    'success' => false,
                    'message' => 'Election is already open.'
                ], 400);
            }

            $election->update(['status' => 'Open']);
            return response()->json([
                'success' => true,
                'message' => 'Election opened successfully.',
                'newStatus' => 'Close Election'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to open election: ' . $e->getMessage()
            ], 500);
        }
    }

    public function close(Request $request, Election $election)
    {
        try {
            if ($election->status === 'Closed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Election is already closed.'
                ], 400);
            }

            $election->update(['status' => 'Closed']);
            return response()->json([
                'success' => true,
                'message' => 'Election closed successfully.',
                'newStatus' => 'Open Election'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to close election: ' . $e->getMessage()
            ], 500);
        }
    }
}
