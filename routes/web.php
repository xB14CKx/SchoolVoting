<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ElectionCandidateController;
use App\Http\Controllers\ElectionResultController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\EligibilityController; // Add this import
use Illuminate\Support\Facades\Route;

// Guest Routes (unauthenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/', [PagesController::class, 'home'])->name('home');
    Route::get('/about', [PagesController::class, 'about'])->name('about');
    Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
    Route::get('/eligibility', [EligibilityController::class, 'index'])->name('eligibility'); // Updated to use EligibilityController
    Route::post('/eligibility', [EligibilityController::class, 'check'])->name('eligibility.check'); // Added POST route
    Route::get('/login', [PagesController::class, 'login'])->name('login');
    Route::get('/registration', [PagesController::class, 'registration'])->name('registration');
});

// Authenticated Routes (authenticated and verified users)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard and User Info
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
    Route::get('/userinfo', [PagesController::class, 'userinfo'])->name('userinfo');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Election System Routes
    Route::resource('candidates', CandidateController::class)->names('candidates');
    Route::resource('users', UserController::class)->names('users')->middleware('admin');

    // Election-related Routes (grouped under elections/{election})
    Route::prefix('elections')->name('elections.')->group(function () {
        // Election Resource Routes
        Route::resource('/', ElectionController::class)
            ->parameters(['' => 'election'])
            ->names('elections');

        // Nested Election Routes
        Route::prefix('{election}')->whereNumber('election')->group(function () {
            // Election Candidates Routes
            Route::get('candidates/create', [ElectionCandidateController::class, 'create'])
                ->name('candidates.create');
            Route::post('candidates', [ElectionCandidateController::class, 'store'])
                ->name('candidates.store');
            Route::delete('candidates/{candidate}', [ElectionCandidateController::class, 'destroy'])
                ->name('candidates.destroy')
                ->whereNumber('candidate');

            // Election Results Routes
            Route::get('results', [ElectionResultController::class, 'show'])
                ->name('results.show');
            Route::post('results', [ElectionResultController::class, 'update'])
                ->name('results.update')
                ->middleware('admin');

            // Voting Routes
            Route::get('vote', [VoteController::class, 'create'])
                ->name('vote.create')
                ->middleware('voter');
            Route::post('vote', [VoteController::class, 'store'])
                ->name('vote.store')
                ->middleware('voter');
        });
    });
});

// Laravel Authentication Routes
require __DIR__.'/auth.php';