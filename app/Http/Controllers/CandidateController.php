<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Candidate;
use App\Http\Controllers\ElectionController;

class CandidateController extends Controller
{
    // Create a new candidate
public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'year_level' => 'required|in:1st,2nd,3rd,4th',
            'platform' => 'nullable|string',
            'position_id' => 'required|exists:positions,position_id',
            'program_id' => 'required|exists:programs,program_id',
            'partylist_id' => 'required|exists:partylists,partylist_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $candidate = Candidate::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'middle_name' => $validatedData['middle_name'],
                'year_level' => $validatedData['year_level'],
                'platform' => $validatedData['platform'],
                'position_id' => $validatedData['position_id'],
                'program_id' => $validatedData['program_id'],
                'partylist_id' => $validatedData['partylist_id']
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('candidates', 'public');
                $candidate->image = $imagePath;
                $candidate->save();
            }

            $election = ElectionController::getOrCreateCurrentElection();
            $election->candidates()->attach($candidate->candidate_id);

            return response()->json([
                'success' => true,
                'message' => 'Candidate added successfully and attached to election',
                'data' => $candidate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding candidate: ' . $e->getMessage()
            ], 500);
        }
    }
    public function admin()
    {
        $candidates = Candidate::with(['program', 'partylist', 'position'])->get();
        Log::debug('Candidates retrieved for admin:', $candidates->toArray());
        return view('votings.admin', compact('candidates'));
    }

    // Return candidate details for editing
    public function show(Candidate $candidate)
    {
        return response()->json([
            'candidate_id' => $candidate->candidate_id,
            'first_name' => $candidate->first_name,
            'last_name' => $candidate->last_name,
            'middle_name' => $candidate->middle_name,
            'year_level' => $candidate->year_level,
            'platform' => $candidate->platform,
            'image' => $candidate->image ? asset('storage/' . $candidate->image) : null,
            'position' => [
                'position_id' => $candidate->position->position_id,
                'position_name' => $candidate->position->name // Use 'name' as per your Blade template
            ],
            'program' => [
                'program_id' => $candidate->program->program_id,
                'program_name' => $candidate->program->program_name
            ],
            'partylist' => [
                'partylist_id' => $candidate->partylist->partylist_id,
                'partylist_name' => $candidate->partylist->partylist_name
            ]
        ]);
    }

    // Update an existing candidate
    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        // Validate request data
        $validated = $request->validate([
            'partylist_id' => 'required|exists:partylists,partylist_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'year_level' => 'required|in:1st,2nd,3rd,4th',
            'program_id' => 'required|exists:programs,program_id',
            'platform' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle image upload if present
            if ($request->hasFile('image')) {
                // Delete old image if necessary
                if ($candidate->image) {
                    Storage::disk('public')->delete($candidate->image);
                }
                $path = $request->file('image')->store('candidates', 'public');
                $validated['image'] = $path;
            } else {
                // Preserve the existing image if no new image is uploaded
                $validated['image'] = $candidate->image;
            }

            // Update candidate
            $candidate->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Candidate updated successfully',
                'data' => $candidate
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating candidate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete a candidate
    public function destroy(Candidate $candidate)
    {
        try {
            // Delete the candidate's image if it exists
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }

            // Detach the candidate from any elections
            $candidate->elections()->detach();

            $candidate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Candidate deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting candidate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    // Fetch candidates for the elect page
    public function index()
    {
        $candidates = Candidate::with(['position', 'program', 'partylist'])
            ->orderBy('position_id')
            ->get();

        return view('votings.elect', compact('candidates'));
    }
}
