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
use App\Http\Controllers\Auth\TestEmailController;
use Illuminate\Support\Facades\Route;
use App\Models\Program;
use App\Models\Partylist;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Student;

// Guest Routes (unauthenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/', [PagesController::class, 'home'])->name('home');
    Route::get('/about', [PagesController::class, 'about'])->name('about');
    Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
    Route::get('/eligibility', function () {
        return view('eligibility');
    })->name('eligibility');
    Route::get('/registration', function () {
        return view('registration');
    })->name('registration');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/vote-counting', [App\Http\Controllers\VoteController::class, 'voteCountingPage'])->name('vote-counting');
    Route::get('/vote-counting/demographics', [\App\Http\Controllers\VoteCountController::class, 'getDemographicBreakdown']);

    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', function () {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin');
            } elseif (auth()->user()->isStudent()) {
                return redirect()->route('elect');
            } else {
                abort(403, 'Unauthorized action.');
            }
        })->name('dashboard');

        Route::post('/send-test-email', [TestEmailController::class, 'send'])->name('send.test.email');

        Route::get('/elect', [VoteController::class, 'elect'])->name('elect');

        Route::middleware('can:is-student')->group(function () {
            Route::get('/votings/elect', [VoteController::class, 'elect'])->name('votings.elect');
        });

        Route::get('/result', function () {
            return view('votings.result');
        })->name('result');

        Route::get('/userinfo', function () {
            $user = auth()->user();
            $student = Student::with('program')->where('email', $user->email)->first();
            return view('votings.userinfo', compact('student'));
        })->name('userinfo');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/reports', function () {
        return view('votings.reports');
    })->name('reports');

    Route::post('/student/upload-profile-image', [StudentController::class, 'uploadProfileImage'])->name('student.uploadProfileImage');

    Route::post('/student/update-contact', [StudentController::class, 'updateContact'])->name('student.updateContact');

    // Admin-only Routes
    Route::middleware('can:is-admin')->group(function () {
        Route::get('/admin', function () {
            $programs = Program::all();
            $partylists = Partylist::all();
            $candidates = Candidate::with(['program', 'partylist', 'position'])->get();
            $currentElection = \App\Http\Controllers\ElectionController::getOrCreateCurrentElection();
            return view('votings.admin', compact('programs', 'partylists', 'candidates', 'currentElection'));
        })->name('admin');

        Route::resource('users', UserController::class)->names('users');
        Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');

        Route::get('/file-upload', function () {
            return view('votings.file-upload');
        })->name('file-upload');

        Route::get('/fetch-students', [FileUploadController::class, 'fetchStudents'])->name('fetch.students');

        Route::get('/upload', [FileUploadController::class, 'index'])->name('upload.index');
        Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload.store');

        Route::resource('candidates', CandidateController::class)->names('candidates');
        Route::resource('elections', ElectionController::class)->names('elections');
        Route::get('/candidates/{candidate}/has-votes', [CandidateController::class, 'hasVotes'])->name('candidates.hasVotes');

        // Election status routes
        Route::post('/elections/{election}/open', [ElectionController::class, 'open'])->name('elections.open');
        Route::post('/elections/{election}/close', [ElectionController::class, 'close'])->name('elections.close');

        // New route for fetching candidates by year
        Route::get('/candidates/by-year/{year}', [CandidateController::class, 'getByYear'])->name('candidates.byYear');

        Route::prefix('elections/{election}')->whereNumber('election')->group(function () {
            Route::get('/candidates/create', [ElectionCandidateController::class, 'create'])->name('elections.candidates.create');
            Route::post('/candidates', [ElectionCandidateController::class, 'store'])->name('elections.candidates.store');
            Route::delete('/candidates/{candidate}', [ElectionCandidateController::class, 'destroy'])
                ->whereNumber('candidate')->name('elections.candidates.destroy');

            Route::get('/results', [ElectionResultController::class, 'show'])->name('votings.results.show');
            Route::post('/results', [ElectionResultController::class, 'update'])->name('votings.results.update');
        });

        Route::get('/students/search/{id}', [StudentController::class, 'search'])->name('students.search');
    });

    Route::prefix('elections/{election}')->whereNumber('election')->middleware('voter')->group(function () {
        Route::get('/vote', [VoteController::class, 'create'])->name('elections.vote.create');
        Route::post('/vote', [VoteController::class, 'store'])->name('elections.vote.store');
    });

    Route::post('/votes', [VoteController::class, 'store'])->name('votes.store');
});

require __DIR__ . '/auth.php';
