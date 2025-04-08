<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;

class CandidateController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'nullable',
            'year_level' => 'required|in:1st,2nd,3rd,4th',
            'program_id' => 'required|integer',
            'partylist_id' => 'required|integer',
            'position_id' => 'required|integer',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('candidates', 'public');
        }

        $candidate = Candidate::create($validated);

        return response()->json(['success' => true, 'candidate' => $candidate]);
    }
}
