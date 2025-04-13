<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;

class CandidateController extends Controller
{
    // Create a new candidate
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'year_level'  => 'required|in:1st,2nd,3rd,4th',
            'program_id'  => 'required|integer',
            'partylist_id'=> 'required|integer',
            'position_id' => 'required|integer',
            'image'       => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Store the image on the 'public' disk in the 'candidates' folder and store the relative path
            $validated['image'] = $request->file('image')->store('candidates', 'public');
        }

        $candidate = Candidate::create($validated);

        return response()->json(['success' => true, 'candidate' => $candidate]);
    }

    // Return candidate details for editing
    public function show(Candidate $candidate)
    {
        // Eager load relationships so you can access them in the JSON response.
        $candidate->load(['program', 'partylist', 'position']);
        // Construct image URL if necessary
        $candidate->image_url = $candidate->image ? asset('storage/' . $candidate->image) : null;
        $candidate->position_name = optional($candidate->position)->name;
        return response()->json(['success' => true, 'candidate' => $candidate]);
    }

    // Update an existing candidate
    public function update(Request $request, Candidate $candidate)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'middle_name' => 'nullable',
                'year_level' => 'required|in:1st,2nd,3rd,4th',
                'program_id' => 'required|integer',
                'partylist_id' => 'required|integer',
                'image' => 'nullable|image|max:2048'
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($candidate->image) {
                    \Storage::disk('public')->delete($candidate->image);
                }
                $validated['image'] = $request->file('image')->store('candidates', 'public');
            }

            $candidate->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Candidate updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete a candidate
    public function destroy(Candidate $candidate)
    {
        try {
            // Delete the candidate's image if it exists
            if ($candidate->image) {
                \Storage::disk('public')->delete($candidate->image);
            }
            
            $candidate->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Candidate deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete candidate: ' . $e->getMessage()
            ], 500);
        }
    }
}
