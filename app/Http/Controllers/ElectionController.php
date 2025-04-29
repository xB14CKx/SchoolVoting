<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    // Helper method to find or create an election for the current year
public static function getOrCreateCurrentElection()
{
    $currentYear = date('Y');

    return DB::transaction(function () use ($currentYear) {
        // Lock the table to prevent race conditions
        $election = Election::where('year', $currentYear)
            ->lockForUpdate()
            ->first();

        if (!$election) {
            $election = Election::create(['year' => $currentYear]);
        }

        return $election;
    });
}

    public function index()
    {
        $elections = Election::all();
        return view('elections.index', compact('elections'));
    }

    public function create()
    {
        return view('elections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1) . '|unique:elections,year',
        ]);

        try {
            Election::create($validated);

            return redirect()->route('elections.index')->with('success', 'Election created successfully.');
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
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1) . '|unique:elections,year,' . $election->election_id,
        ]);

        try {
            $election->update($validated);

            return redirect()->route('elections.index')->with('success', 'Election updated successfully.');
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
            return redirect()->route('elections.index')->with('success', 'Election deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('elections.index')
                ->with('error', 'Failed to delete election: ' . $e->getMessage());
        }
    }
}
