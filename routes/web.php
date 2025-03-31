<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ElectionCandidateController;
use App\Http\Controllers\ElectionResultController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Guest Routes (unauthenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/', [PagesController::class, 'home'])->name('home');
    Route::get('/about', [PagesController::class, 'about'])->name('about');
    Route::get('/contact', [PagesController::class, 'contact'])->name('contact');

});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');

    // User Routes
    Route::get('/elect', function() {
        return view('elect');
    })->name('elect');

    Route::get('/result', function() {
        return view('result');
    })->name('result');

    Route::get('/vote-counting', function() {
        return view('vote-counting');
    })->name('vote-counting');

    Route::get('/userinfo', [PagesController::class, 'userinfo'])->name('userinfo');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin', function() {
            return view('admin');
        })->name('admin');

        Route::resource('users', UserController::class)->names('users');
        Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    });

    // Election System Routes
    Route::resource('candidates', CandidateController::class)->names('candidates');

    // Election-related Routes
    Route::prefix('elections')->name('elections.')->group(function () {
        Route::resource('/', ElectionController::class)
            ->parameters(['' => 'election'])
            ->names('elections');

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
            Route::middleware('voter')->group(function () {
                Route::get('vote', [VoteController::class, 'create'])
                    ->name('vote.create');
                Route::post('vote', [VoteController::class, 'store'])
                    ->name('vote.store');
            });
        });
    });
});

// Laravel Authentication Routes (Breeze)
require __DIR__.'/auth.php';