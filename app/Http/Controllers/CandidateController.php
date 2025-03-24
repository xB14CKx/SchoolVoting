<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    /**
     * Display a listing of the candidates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $candidates = Candidate::with('position')->get(); // Include position for display
        return view('candidates.index', compact('candidates'));
    }

    /**
     * Show the form for creating a new candidate.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $positions = Position::all(); // Get all positions for the dropdown
        return view('candidates.create', compact('positions'));
    }

    /**
     * Store a newly created candidate in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'year_level' => 'required|in:1st,2nd,3rd,4th',
            'program' => 'required|string|max:30',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        try {
            $candidate = new Candidate($validated);

            if ($request->hasFile('image')) {
                // Store the image in the 'public/candidates' directory and save the path
                $path = $request->file('image')->store('candidates', 'public');
                $candidate->image = $path;
            }

            $candidate->save();

            return redirect()->route('candidates.index')
                ->with('success', 'Candidate created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('candidates.create')
                ->with('error', 'Failed to create candidate: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified candidate.
     *
     * @param \App\Models\Candidate $candidate
     * @return \Illuminate\View\View
     */
    public function show(Candidate $candidate)
    {
        $candidate->load('position'); // Load the position for display
        return view('candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified candidate.
     *
     * @param \App\Models\Candidate $candidate
     * @return \Illuminate\View\View
     */
    public function edit(Candidate $candidate)
    {
        $positions = Position::all(); // Get all positions for the dropdown
        return view('candidates.edit', compact('candidate', 'positions'));
    }

    /**
     * Update the specified candidate in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Candidate $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'year_level' => 'required|in:1st,2nd,3rd,4th',
            'program' => 'required|string|max:30',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $candidate->fill($validated);

            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($candidate->image) {
                    Storage::disk('public')->delete($candidate->image);
                }
                // Store the new image
                $path = $request->file('image')->store('candidates', 'public');
                $candidate->image = $path;
            }

            $candidate->save();

            return redirect()->route('candidates.index')
                ->with('success', 'Candidate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('candidates.edit', ['candidate' => $candidate->id])
                ->with('error', 'Failed to update candidate: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified candidate from storage.
     *
     * @param \App\Models\Candidate $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Candidate $candidate)
    {
        try {
            // Delete the image from storage if it exists
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }

            $candidate->delete();
            return redirect()->route('candidates.index')
                ->with('success', 'Candidate deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('candidates.index')
                ->with('error', 'Failed to delete candidate: ' . $e->getMessage());
        }
    }
}