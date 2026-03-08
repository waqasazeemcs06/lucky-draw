<?php

use App\Http\Controllers\Admin\DrawController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\PrizeController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::resource('draws', DrawController::class);
Route::get('luck-draw', [DrawController::class, 'luckDraw'])->name('draw.luck-draw');
Route::resource('prizes', PrizeController::class);
Route::resource('participants', ParticipantController::class);



