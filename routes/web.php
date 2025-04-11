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
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

// Guest Routes (unauthenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/', [PagesController::class, 'home'])->name('home');
    Route::get('/about', [PagesController::class, 'about'])->name('about');
    Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

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

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reports Route
    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

    // Admin-only Routes
    Route::middleware('can:is-admin')->group(function () {
        Route::get('/admin', function () {
            return view('votings.admin');
        })->name('admin');

        Route::resource('users', UserController::class)->names('users');
        Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');

        Route::get('/file-upload', function () {
            return view('votings.file-upload');
        })->name('file-upload');

        // File Upload Routes
        Route::get('/upload', [FileUploadController::class, 'index'])->name('upload.index');
        Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload.store');

        // Candidates Management
        Route::resource('candidates', CandidateController::class)->names('candidates');

        // Elections Management
        Route::resource('elections', ElectionController::class)->names('elections');

        // Election Subroutes (Admin)
        Route::prefix('elections/{election}')->whereNumber('election')->group(function () {
            Route::get('candidates/create', [ElectionCandidateController::class, 'create'])->name('elections.candidates.create');
            Route::post('candidates', [ElectionCandidateController::class, 'store'])->name('elections.candidates.store');
            Route::delete('candidates/{candidate}', [ElectionCandidateController::class, 'destroy'])
                ->whereNumber('candidate')->name('elections.candidates.destroy');

            Route::get('results', [ElectionResultController::class, 'show'])->name('elections.results.show');
            Route::post('results', [ElectionResultController::class, 'update'])->name('elections.results.update');
        });
    });

    // Voting Routes for voters only
    Route::prefix('elections/{election}')->whereNumber('election')->middleware('voter')->group(function () {
        Route::get('vote', [VoteController::class, 'create'])->name('elections.vote.create');
        Route::post('vote', [VoteController::class, 'store'])->name('elections.vote.store');
    });
});

require __DIR__ . '/auth.php';
