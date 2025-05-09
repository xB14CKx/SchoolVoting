<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Candidate;
use App\Models\Partylist;
use App\Models\Program;
use App\Models\Election;
use App\Http\Controllers\ElectionController;

class CandidateController extends Controller
{
    // Create a new candidate
    public function store(Request $request)
    {
        Log::info('Received candidate data:', $request->all());

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
            DB::beginTransaction();

            // Check for duplicate candidate
            $existingCandidate = Candidate::where('first_name', $validatedData['first_name'])
                ->where('last_name', $validatedData['last_name'])
                ->where('position_id', $validatedData['position_id'])
                ->lockForUpdate()
                ->first();

            if ($existingCandidate) {
                DB::rollBack();
                Log::warning('Duplicate candidate detected:', $validatedData);
                return response()->json([
                    'success' => false,
                    'message' => 'A candidate with this name is already running for this position.'
                ], 422);
            }

            // Check if partylist is already used for this position
            $existingPartylist = Candidate::where('position_id', $validatedData['position_id'])
                ->where('partylist_id', $validatedData['partylist_id'])
                ->lockForUpdate()
                ->first();

            if ($existingPartylist) {
                DB::rollBack();
                Log::warning('Partylist already used for position:', $validatedData);
                return response()->json([
                    'success' => false,
                    'message' => 'A candidate from this partylist is already running for this position.'
                ], 422);
            }

            // Create the candidate
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

            // Handle image upload if present
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('candidates', 'public');
                $candidate->image = $imagePath;
                $candidate->save();
            }

            // Find or create the current year's election
            $election = ElectionController::getOrCreateCurrentElection();

            // Check if candidate is already attached to the election
            if (!$election->candidates()->where('election_candidates.candidate_id', $candidate->candidate_id)->exists()) {
                $election->candidates()->attach($candidate->candidate_id, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Log::info('Created candidate and attached to election:', [
                'candidate' => $candidate->toArray(),
                'election_id' => $election->election_id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Candidate added successfully and attached to election',
                'data' => $candidate
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating candidate: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error adding candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    // Display the admin page with candidates
    public function admin()
    {
        $candidates = Candidate::with(['program', 'partylist', 'position'])->get();
        $partylists = Partylist::all();
        $programs = Program::all();

        Log::debug('Candidates retrieved for admin:', $candidates->toArray());
        return view('votings.admin', compact('candidates', 'partylists', 'programs'));
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
                'position_name' => $candidate->position->name
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

        // Validate request data (excluding position_id since we won't update it)
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
            // Start a transaction to ensure atomicity
            DB::beginTransaction();

            // Use the candidate's existing position_id for the duplicate check
            $positionId = $candidate->position_id;

            // Check for duplicate candidate (same first_name, last_name, and position_id)
            $existingCandidate = Candidate::where('first_name', $validated['first_name'])
                ->where('last_name', $validated['last_name'])
                ->where('position_id', $positionId)
                ->where('candidate_id', '!=', $id)
                ->lockForUpdate()
                ->first();

            if ($existingCandidate) {
                DB::rollBack();
                Log::warning('Duplicate candidate detected during update:', $validated);
                return response()->json([
                    'success' => false,
                    'message' => 'A candidate with this name is already running for this position.'
                ], 422);
            }

            // Check if partylist is already used for this position by another candidate
            $existingPartylist = Candidate::where('position_id', $positionId)
                ->where('partylist_id', $validated['partylist_id'])
                ->where('candidate_id', '!=', $id)
                ->lockForUpdate()
                ->first();

            if ($existingPartylist) {
                DB::rollBack();
                Log::warning('Partylist already used for position during update:', $validated);
                return response()->json([
                    'success' => false,
                    'message' => 'A candidate from this partylist is already running for this position.'
                ], 422);
            }

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

            // Ensure position_id is not updated by excluding it from the validated data
            unset($validated['position_id']);

            // Update candidate with the validated data
            $candidate->update($validated);

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Candidate updated successfully',
                'data' => $candidate
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
            // Start a transaction
            DB::beginTransaction();

            // Delete the candidate's image if it exists
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }

            // Detach the candidate from any elections
            $candidate->elections()->detach();

            // Delete the candidate
            $candidate->delete();

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Candidate deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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

    // Fetch candidates by year for the admin page
    public function getByYear($year)
    {
        try {
            // Find the election for the specified year
            $election = Election::where('year', $year)->first();

            if (!$election) {
                Log::warning("Election not found for year: {$year}");
                return response()->json([
                    'success' => false,
                    'message' => 'Election not found for the selected year.'
                ], 404);
            }

            // Fetch candidates associated with the election
            $candidates = Candidate::whereHas('elections', function ($query) use ($election) {
                $query->where('election_candidates.election_id', $election->election_id);
            })
            ->with(['position', 'program', 'partylist'])
            ->get();

            Log::info("Fetched candidates for year {$year}:", $candidates->toArray());

            return response()->json([
                'success' => true,
                'candidates' => $candidates
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching candidates by year: ' . $e->getMessage(), [
                'year' => $year,
                'exception' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error fetching candidates: ' . $e->getMessage()
            ], 500);
        }
    }
}
