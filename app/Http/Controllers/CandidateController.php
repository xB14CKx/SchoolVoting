<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Candidate;
use App\Models\Partylist;
use App\Models\Program;
use App\Http\Controllers\ElectionController;
use Illuminate\Validation\ValidationException;

class CandidateController extends Controller
{
    /**
     * Display the admin page with candidates, partylists, and programs.
     *
     * @return \Illuminate\View\View
     */
    public function admin()
    {
        try {
            $candidates = Candidate::with(['program', 'partylist', 'position'])->get();
            $partylists = Partylist::all();
            $programs = Program::all();

            Log::info('Candidates retrieved for admin page', [
                'candidate_count' => $candidates->count(),
                'partylist_count' => $partylists->count(),
                'program_count' => $programs->count()
            ]);

            if ($candidates->isEmpty()) {
                Log::info('No candidates found in database');
            }

            return view('votings.admin', compact('candidates', 'partylists', 'programs'));
        } catch (\Exception $e) {
            Log::error('Error loading admin page: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to load admin page. Please try again.']);
        }
    }

    /**
     * Fetch candidates for the elect page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $candidates = Candidate::with(['position', 'program', 'partylist'])
                ->orderBy('position_id')
                ->get();

            Log::info('Candidates retrieved for elect page', [
                'candidate_count' => $candidates->count()
            ]);

            return view('votings.elect', compact('candidates'));
        } catch (\Exception $e) {
            Log::error('Error loading elect page: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to load elect page. Please try again.']);
        }
    }

    /**
     * Store a new candidate and attach to the current election.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Received candidate store request', $request->all());

        try {
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

            DB::beginTransaction();

            // Check for duplicate candidate
            $existingCandidate = Candidate::where('first_name', $validatedData['first_name'])
                ->where('last_name', $validatedData['last_name'])
                ->where('position_id', $validatedData['position_id'])
                ->lockForUpdate()
                ->first();

            if ($existingCandidate) {
                DB::rollBack();
                Log::warning('Duplicate candidate detected', $validatedData);
                return response()->json([
                    'success' => false,
                    'message' => 'A candidate with this name is already running for this position.'
                ], 422);
            }

            // Create candidate
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

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('candidates', 'public');
                $candidate->image = $imagePath;
                $candidate->save();
            }

            // Attach to current election
            $election = ElectionController::getOrCreateCurrentElection();
            Log::info('Election retrieved or created', ['election_id' => $election->election_id]);

            if (!$election->candidates()->where('election_candidates.candidate_id', $candidate->candidate_id)->exists()) {
                $election->candidates()->attach($candidate->candidate_id, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            Log::info('Candidate created and attached to election', [
                'candidate_id' => $candidate->candidate_id,
                'election_id' => $election->election_id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Candidate added successfully',
                'data' => $candidate->load(['position', 'program', 'partylist'])
            ], 201);
        } catch (ValidationException $e) {
            Log::error('Validation failed in store: ' . json_encode($e->errors()), [
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating candidate: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to add candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return candidate details for editing.
     *
     * @param \App\Models\Candidate $candidate
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Candidate $candidate)
    {
        try {
            Log::info('Fetching candidate for edit', ['candidate_id' => $candidate->candidate_id]);

            return response()->json([
                'success' => true,
                'data' => [
                    'candidate_id' => $candidate->candidate_id,
                    'first_name' => $candidate->first_name,
                    'last_name' => $candidate->last_name,
                    'middle_name' => $candidate->middle_name,
                    'year_level' => $candidate->year_level,
                    'platform' => $candidate->platform,
                    'image' => $candidate->image ? asset('storage/' . $candidate->image) : null,
                    'position' => [
                        'position_id' => $candidate->position->position_id,
                        'position_name' => $candidate->position->position_name
                    ],
                    'program' => [
                        'program_id' => $candidate->program->program_id,
                        'program_name' => $candidate->program->program_name
                    ],
                    'partylist' => [
                        'partylist_id' => $candidate->partylist->partylist_id,
                        'partylist_name' => $candidate->partylist->partylist_name
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching candidate: ' . $e->getMessage(), [
                'candidate_id' => $candidate->candidate_id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing candidate.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        Log::info('Received candidate update request', array_merge($request->all(), ['candidate_id' => $id]));

        try {
            $candidate = Candidate::findOrFail($id);

            $validated = $request->validate([
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

            DB::beginTransaction();

            // Check for duplicate candidate
            $existingCandidate = Candidate::where('first_name', $validated['first_name'])
                ->where('last_name', $validated['last_name'])
                ->where('position_id', $validated['position_id'])
                ->where('candidate_id', '!=', $id)
                ->lockForUpdate()
                ->first();

            if ($existingCandidate) {
                DB::rollBack();
                Log::warning('Duplicate candidate detected during update', $validated);
                return response()->json([
                    'success' => false,
                    'message' => 'A candidate with this name is already running for this position.'
                ], 422);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                if ($candidate->image) {
                    Storage::disk('public')->delete($candidate->image);
                }
                $path = $request->file('image')->store('candidates', 'public');
                $validated['image'] = $path;
            } else {
                $validated['image'] = $candidate->image;
            }

            // Update candidate
            $candidate->update($validated);

            Log::info('Candidate updated successfully', ['candidate_id' => $candidate->candidate_id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Candidate updated successfully',
                'data' => $candidate->load(['position', 'program', 'partylist'])
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation failed in update: ' . json_encode($e->errors()), [
                'candidate_id' => $id,
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating candidate: ' . $e->getMessage(), [
                'candidate_id' => $id,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a candidate and associated data.
     *
     * @param \App\Models\Candidate $candidate
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Candidate $candidate)
    {
        Log::info('Received candidate delete request', ['candidate_id' => $candidate->candidate_id]);

        try {
            DB::beginTransaction();

            // Delete image if exists
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
                Log::info('Candidate image deleted', ['image_path' => $candidate->image]);
            }

            // Detach from elections
            $candidate->elections()->detach();
            Log::info('Candidate detached from elections', ['candidate_id' => $candidate->candidate_id]);

            // Delete candidate
            $candidate->delete();

            Log::info('Candidate deleted successfully', ['candidate_id' => $candidate->candidate_id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Candidate deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting candidate: ' . $e->getMessage(), [
                'candidate_id' => $candidate->candidate_id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete candidate: ' . $e->getMessage()
            ], 500);
        }
    }
}
