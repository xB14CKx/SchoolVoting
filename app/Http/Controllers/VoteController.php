<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'votes' => 'required|array',
            'votes.*' => 'required|exists:candidates,id'
        ]);

        try {
            // Check if user has already voted
            if (Vote::where('user_id', Auth::id())->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already voted'
                ], 400);
            }

            // Store votes
            foreach ($validated['votes'] as $position => $candidateId) {
                Vote::create([
                    'user_id' => Auth::id(),
                    'candidate_id' => $candidateId,
                    'position' => $position
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Votes submitted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit votes: ' . $e->getMessage()
            ], 500);
        }
    }
} 