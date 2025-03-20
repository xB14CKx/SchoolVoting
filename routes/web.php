<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ElectionCandidateController;
use App\Http\Controllers\ElectionResultController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Landing page route (you have two identical routes for '/', I'll keep the first one)
Route::get('/', function () {
    return view('landing');
});

// Login page route
Route::get('/login', function () {
    return view('login'); // resources/views/login.blade.php
})->middleware('guest')->name('login');

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Eligibility page route
Route::get('/eligibility', function () {
    return view('eligibility'); // resources/views/eligibility.blade.php
})->name('eligibility');

// Profile routes (already grouped under 'auth' middleware)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Election system routes (grouped under 'auth' middleware for security)
Route::middleware('auth')->group(function () {
    // Resource routes for CRUD operations
    Route::resource('candidates', CandidateController::class);
    Route::resource('elections', ElectionController::class);
    Route::resource('users', UserController::class);

    // Custom routes for election candidates
    Route::get('elections/{election}/candidates/create', [ElectionCandidateController::class, 'create'])->name('election_candidates.create');
    Route::post('elections/{election}/candidates', [ElectionCandidateController::class, 'store'])->name('election_candidates.store');
    Route::delete('elections/{election}/candidates/{candidate}', [ElectionCandidateController::class, 'destroy'])->name('election_candidates.destroy');

    // Custom routes for election results
    Route::get('elections/{election}/results', [ElectionResultController::class, 'show'])->name('election_results.show');
    Route::post('elections/{election}/results', [ElectionResultController::class, 'update'])->name('election_results.update');

    // Custom routes for voting
    Route::get('elections/{election}/vote', [VoteController::class, 'create'])->name('votes.create');
    Route::post('elections/{election}/vote', [VoteController::class, 'store'])->name('votes.store');
});

// Include Laravel's default authentication routes
require __DIR__.'/auth.php';