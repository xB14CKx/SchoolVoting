<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ElectionCandidateController;
use App\Http\Controllers\ElectionResultController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [PagesController::class, 'home'])->name('home');
    Route::get('/about', [PagesController::class, 'about'])->name('about');
    Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
    Route::get('/eligibility', [PagesController::class, 'eligibility'])->name('eligibility');
    Route::get('/login', [PagesController::class, 'login'])->name('login');
    Route::get('/registration', [PagesController::class, 'registration'])->name('registration');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
    Route::get('/userinfo', [PagesController::class, 'userinfo'])->name('userinfo');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Election System Routes
    Route::resource('candidates', CandidateController::class)->names('candidates');
    Route::resource('elections', ElectionController::class)->names('elections');
    Route::resource('users', UserController::class)->names('users')->middleware('admin');

    // Election Candidates Routes
    Route::get('elections/{election}/candidates/create', [ElectionCandidateController::class, 'create'])
        ->name('election_candidates.create')
        ->where(['election' => '[0-9]+']);
    Route::post('elections/{election}/candidates', [ElectionCandidateController::class, 'store'])
        ->name('election_candidates.store')
        ->where(['election' => '[0-9]+']);
    Route::delete('elections/{election}/candidates/{candidate}', [ElectionCandidateController::class, 'destroy'])
        ->name('election_candidates.destroy')
        ->where(['election' => '[0-9]+', 'candidate' => '[0-9]+']);

    // Election Results Routes
    Route::get('elections/{election}/results', [ElectionResultController::class, 'show'])
        ->name('election_results.show')
        ->where(['election' => '[0-9]+']);
    Route::post('elections/{election}/results', [ElectionResultController::class, 'update'])
        ->name('election_results.update')
        ->where(['election' => '[0-9]+']);

    // Voting Routes
    Route::get('elections/{election}/vote', [VoteController::class, 'create'])
        ->name('votes.create')
        ->where(['election' => '[0-9]+']);
    Route::post('elections/{election}/vote', [VoteController::class, 'store'])
        ->name('votes.store')
        ->where(['election' => '[0-9]+']);
});

// Laravel Authentication Routes
require __DIR__.'/auth.php';