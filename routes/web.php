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

// Guest Routes (unauthenticated users) - No changes needed here
Route::middleware('guest')->group(function () {
    Route::get('/', [PagesController::class, 'home'])->name('home');
    Route::get('/about', [PagesController::class, 'about'])->name('about');
    Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
});

// Authenticated Routes (combined)
Route::middleware('auth')->group(function () {
    // Routes for all authenticated users (with 'verified' middleware where needed)
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', function () {
            return view('votings.dashboard');
        })->name('dashboard');
        Route::get('/elect', function () {
            return view('votings.elect');
        })->name('elect');
        Route::get('/result', function () {
            return view('votings.result');
        })->name('result');
        Route::get('/vote-counting', function () {
            return view('votings.vote-counting');
        })->name('vote-counting');
        Route::get('/userinfo', function () {
            return view('votings.userinfo');
        })->name('userinfo');
    });

    // Profile Routes (available to all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // General Election System Routes (available to all authenticated users)
    Route::resource('candidates', CandidateController::class)->names('candidates');

    // Election-related Routes
    Route::prefix('elections')->name('elections.')->group(function () {
        Route::resource('', ElectionController::class)
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
                ->middleware('admin'); // Admin-only for updating results

            // Voting Routes (voter middleware for voting)
            Route::middleware('voter')->group(function () {
                Route::get('vote', [VoteController::class, 'create'])
                    ->name('vote.create');
                Route::post('vote', [VoteController::class, 'store'])
                    ->name('vote.store');
            });
        });
    });

    // Reports Route (available to all authenticated users)
    Route::get('/reports', function () {
        return view('votings.reports');
    })->name('reports');

    // Admin-only Routes (nested middleware 'admin' check)
    Route::middleware('admin')->group(function () {
        Route::get('/admin', function () {
            return view('votings.admin');
        })->name('admin');

        Route::resource('users', UserController::class)->names('users');
        Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');

        // File Upload Route (admin-only)
        Route::get('/file-upload', function () {
            return view('votings.file-upload');
        })->name('file-upload');
    });
});

require __DIR__ . '/auth.php';
