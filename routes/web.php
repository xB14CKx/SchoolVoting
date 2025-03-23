<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


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

//main pages

Route::get('/userinfo', function() {
    return view('userinfo');
})->name('userinfo');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    return view('landing');
});

require __DIR__.'/auth.php';
