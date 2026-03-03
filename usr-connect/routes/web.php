<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SlotController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// 1. La liste globale
Route::get('/slots', [SlotController::class, 'index'])->middleware(['auth'])->name('slots.index');

// 2. LE FORMULAIRE (Obligatoirement AVANT le paramètre {slot})
Route::get('/slots/create', [SlotController::class, 'create'])->middleware(['auth'])->name('slots.create');

// 3. L'ENREGISTREMENT (POST)
Route::post('/slots', [SlotController::class, 'store'])->middleware(['auth'])->name('slots.store');

// 4. L'INSCRIPTION
Route::post('/slots/{slot}/register', [SlotController::class, 'register'])->middleware(['auth'])->name('slots.register');

Route::delete('/slots/{slot}/unregister', [SlotController::class, 'unregister'])->middleware(['auth'])->name('slots.unregister');

// 5. LE DÉTAIL (En dernier car le {slot} mange tout ce qui passe)
Route::get('/slots/{slot}', [SlotController::class, 'show'])->middleware(['auth'])->name('slots.show');

Route::get('/admin/users', [SlotController::class, 'users'])->name('admin.users');

Route::patch('/admin/users/{user}', [SlotController::class, 'updateRole'])->name('admin.users.update');
