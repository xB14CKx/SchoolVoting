<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Program;
use App\Models\Partylist;
use App\Http\Controllers\CandidateController;
use App\Models\Candidate;


//post routes
Route::post('/candidates', [CandidateController::class, 'store'])->name('candidates.store');
Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('candidates.destroy');
Route::post('/candidates/{candidate}', [CandidateController::class, 'update'])->name('candidates.update');


//guest pages
Route::get('/', function () {
    return view('landing');
});

Route::get('/about', function () {
    return view('about'); // or view('pages.about') if placed in a subfolder
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');


Route::get('/eligibility', function () {
    return view('eligibility'); // resources/views/eligibility.blade.php
})->name('eligibility');

Route::get('/login', function () {
    return view('login'); // resources/views/login.blade.php
})->middleware('guest')->name('login');

//not final -- route for registration page
Route::get('/registration', function () {
    return view('registration'); // resources/views/login.blade.php
})->name('registration');

//Laravel's dashboard template
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//main pages for USER

Route::get('/elect', function() {
    return view('elect');
})->name('elect');

Route::get('/result', function() {
    return view('result');
})->name('result');

Route::get('/vote-counting', function() {
    return view('vote-counting');
})->name('vote-counting');

Route::get('/userinfo', function() {
    return view('userinfo');
})->name('userinfo');

//main pages for ADMIN

Route::get('/admin', function () {
    $programs = Program::all();
    $partylists = Partylist::all();
    $candidates = Candidate::with(['program', 'partylist', 'position'])->get();
    return view('admin', compact('programs', 'partylists', 'candidates'));
})->name('admin');

Route::get('/reports', function() {
    return view('reports');
})->name('reports');

Route::get('/file-upload', function() {
    return view('file-upload');
})->name('file-upload');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    return view('landing');
});

require __DIR__.'/auth.php';
