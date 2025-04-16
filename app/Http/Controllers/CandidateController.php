<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;

class CandidateController extends Controller
{
    // Create a new candidate
    public function store(Request $request)
    {
        // Debug logging
        \Log::info('Received candidate data:', $request->all());
        
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'nullable',
            'year_level' => 'required',
            'platform' => 'nullable|string', // Make sure this validation rule exists
            'position_id' => 'required',
            'program_id' => 'required',
            'partylist_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Debug logging
            \Log::info('Validated data:', $validatedData);
            
            $candidate = Candidate::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'middle_name' => $validatedData['middle_name'],
                'year_level' => $validatedData['year_level'],
                'platform' => $request->platform, // Explicitly set platform
                'position_id' => $validatedData['position_id'],
                'program_id' => $validatedData['program_id'],
                'partylist_id' => $validatedData['partylist_id']
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('candidates', 'public');
                $candidate->image = $imagePath;
                $candidate->save();
            }

            // Debug logging
            \Log::info('Created candidate:', $candidate->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Candidate added successfully',
                'data' => $candidate // Include the created candidate in response
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating candidate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    // Return candidate details for editing
    public function show(Candidate $candidate)
    {
        return response()->json([
            'id' => $candidate->id,
            'first_name' => $candidate->first_name,
            'last_name' => $candidate->last_name,
            'middle_name' => $candidate->middle_name,
            'year_level' => $candidate->year_level,
            'platform' => $candidate->platform,
            'image' => $candidate->image,
            'position' => [
                'name' => $candidate->position->name
            ],
            'program' => [
                'program_name' => $candidate->program->program_name
            ],
            'partylist' => [
                'partylist_name' => $candidate->partylist->partylist_name
            ]
        ]);
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

    // Add this method to your existing CandidateController
    public function index()
    {
        $candidates = Candidate::with(['position', 'program', 'partylist'])
            ->orderBy('position_id')
            ->get();
        
        return view('elect', compact('candidates'));
    }
}
