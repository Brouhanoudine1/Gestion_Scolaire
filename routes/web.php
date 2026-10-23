<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ElevesController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\MatieresController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/welcome', 'welcome');

Route::get('/dashboard', function (Request $request) {
    return match ($request->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'enseignant' => redirect()->route('teacher.dashboard'),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/eleves', [ElevesController::class, 'store'])->name('students.store');
        Route::post('/enseignants', [EnseignantController::class, 'store'])->name('teachers.store');
        Route::post('/classes', [ClasseController::class, 'store'])->name('classes.store');
        Route::post('/matieres', [MatieresController::class, 'store'])->name('subjects.store');
        Route::post('/affectations', [AffectationController::class, 'store'])->name('assignments.store');
    });

Route::middleware(['auth', 'verified', 'role:enseignant'])
    ->prefix('enseignant')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::get('/evaluations/creer', [TeacherDashboardController::class, 'createEvaluation'])->name('evaluations.create');
        Route::post('/evaluations', [TeacherDashboardController::class, 'storeEvaluation'])->name('evaluations.store');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
