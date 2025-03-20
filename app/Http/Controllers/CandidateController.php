<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Require authentication for all actions
    }

    public function index()
    {
        $candidates = Candidate::all();
        return view('candidates.index', compact('candidates'));
    }

    public function create()
    {
        return view('candidates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'year_level' => 'required|in:1st,2nd,3rd,4th',
            'program' => 'required|string|max:30',
            'image' => 'nullable|image|max:2048', // Assuming image upload
        ]);

        $candidate = new Candidate($validated);

        if ($request->hasFile('image')) {
            $candidate->image = file_get_contents($request->file('image')->getRealPath());
        }

        $candidate->save();

        return redirect()->route('candidates.index')->with('success', 'Candidate created successfully.');
    }

    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
    }

    public function edit(Candidate $candidate)
    {
        return view('candidates.edit', compact('candidate'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'year_level' => 'required|in:1st,2nd,3rd,4th',
            'program' => 'required|string|max:30',
            'image' => 'nullable|image|max:2048',
        ]);

        $candidate->fill($validated);

        if ($request->hasFile('image')) {
            $candidate->image = file_get_contents($request->file('image')->getRealPath());
        }

        $candidate->save();

        return redirect()->route('candidates.index')->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return redirect()->route('candidates.index')->with('success', 'Candidate deleted successfully.');
    }
}