<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ElectionCandidateController;
use App\HttpControllers\ElectionResultController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;
use App\Models\Program;
use App\Models\Partylist;
use App\Models\Candidate;

// Guest Routes (unauthenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/', [PagesController::class, 'home'])->name('home'); // Uses PagesController from first file
    Route::get('/about', [PagesController::class, 'about'])->name('about'); // Consistent with both files
    Route::get('/contact', [PagesController::class, 'contact'])->name('contact'); // Consistent with both files
    Route::get('/eligibility', function () {
        return view('eligibility');
    })->name('eligibility'); // From second file
    Route::get('/registration', function () {
        return view('registration');
    })->name('registration'); // From second file
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', function () {
            return view('votings.dashboard'); // Uses 'votings.' prefix from first file
        })->name('dashboard');

        Route::get('/elect', [CandidateController::class, 'index'])->name('elect'); // Uses CandidateController from second file

        Route::get('/result', function () {
            return view('votings.result'); // Uses 'votings.' prefix from first file
        })->name('result');

        Route::get('/vote-counting', function () {
            return view('votings.vote-counting'); // Uses 'votings.' prefix from first file
        })->name('vote-counting');

        Route::get('/userinfo', function () {
            return view('votings.userinfo'); // Uses 'votings.' prefix from first file
        })->name('userinfo');
    });

    // Profile Routes (consistent in both files)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reports Route (consistent in both files)
    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

    // Admin-only Routes
    Route::middleware('can:is-admin')->group(function () {
        Route::get('/admin', function () {
            $programs = Program::all();
            $partylists = Partylist::all();
            $candidates = Candidate::with(['program', 'partylist', 'position'])->get();
            return view('votings.admin', compact('programs', 'partylists', 'candidates')); // Uses 'votings.' prefix and data from second file
        })->name('admin');

        Route::resource('users', UserController::class)->names('users'); // From first file
        Route::post('/students/import', [StudentController::class, 'import'])->name('students.import'); // From first file

        Route::get('/file-upload', function () {
            return view('votings.file-upload'); // Uses 'votings.' prefix from first file
        })->name('file-upload');

        Route::get('/fetch-students', [FileUploadController::class, 'fetchStudents'])->name('fetch.students'); // From first file

        // File Upload Routes
        Route::get('/upload', [FileUploadController::class, 'index'])->name('upload.index'); // From first file
        Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload.store'); // From first file

        // Candidates Management
        Route::resource('candidates', CandidateController::class)->names('candidates'); // From first file, includes store, destroy, update
        // Additional candidate routes from second file are covered by resource route above

        // Elections Management
        Route::resource('elections', ElectionController::class)->names('elections'); // From first file

        // Election Subroutes (Admin)
        Route::prefix('elections/{election}')->whereNumber('election')->group(function () {
            Route::get('candidates/create', [ElectionCandidateController::class, 'create'])->name('elections.candidates.create');
            Route::post('candidates', [ElectionCandidateController::class, 'store'])->name('elections.candidates.store');
            Route::delete('candidates/{candidate}', [ElectionCandidateController::class, 'destroy'])
                ->whereNumber('candidate')->name('elections.candidates.destroy');

            Route::get('results', [ElectionResultController::class, 'show'])->name('elections.results.show');
            Route::post('results', [ElectionResultController::class, 'update'])->name('elections.results.update');
        }); // From first file
    });

    // Voting Routes
    Route::prefix('elections/{election}')->whereNumber('election')->middleware('voter')->group(function () {
        Route::get('vote', [VoteController::class, 'create'])->name('elections.vote.create');
        Route::post('vote', [VoteController::class, 'store'])->name('elections.vote.store');
    }); // From first file

    Route::post('/votes', [VoteController::class, 'store'])->name('votes.store'); // From second file, for multiple votes
});

require __DIR__ . '/auth.php';
