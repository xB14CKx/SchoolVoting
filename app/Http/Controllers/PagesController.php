<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home()
    {
        return view('landing');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function eligibility()
    {
        return view('eligibility');
    }

    public function login()
    {
        return view('login');
    }

    public function registration(Request $request)
    {
        // Get the student_id from the query parameter
        $studentId = $request->query('student_id');

        // If student_id is not provided, redirect to eligibility check
        if (!$studentId) {
            return redirect()->route('eligibility')
                ->with('error', 'Please check your eligibility before registering.');
        }

        return view('registration', compact('studentId'));
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function userinfo()
    {
        return view('userinfo');
    }
}