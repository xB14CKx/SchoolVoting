<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
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

            return redirect()->route('candidates.index')->with('success', 'Candidate created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('candidates.create')->with('error', 'Failed to create candidate: ' . $e->getMessage());
        }
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

            return redirect()->route('candidates.index')->with('success', 'Candidate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('candidates.edit', ['candidate' => $candidate->id])
                ->with('error', 'Failed to update candidate: ' . $e->getMessage());
        }
    }

    public function destroy(Candidate $candidate)
    {
        try {
            // Delete the image from storage if it exists
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }

            $candidate->delete();
            return redirect()->route('candidates.index')->with('success', 'Candidate deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('candidates.index')->with('error', 'Failed to delete candidate: ' . $e->getMessage());
        }
    }
}