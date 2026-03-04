<?php

use App\Http\Controllers\ProfileController;
use App\Models\Slot;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\SlotController;
use App\Models\User;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// 1. La route du Dashboard
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    return Inertia::render('Dashboard', [
        'totalMissions' => \App\Models\Slot::where('start_time', '>', now())->count(),
        'myMissionsCount' => $user->slots()->count(),
        'nextMission' => $user->slots()
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->first(),
        'totalVolunteers' => \App\Models\User::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// 2. La route des Membres (Celle qui crashait)
Route::get('/membres', function () {
    return Inertia::render('Users/Index', [
        'users' => \App\Models\User::withCount('slots')
            ->orderBy('slots_count', 'desc') // On met les plus actifs en haut !
            ->get()
    ]);
})->middleware(['auth', 'verified'])->name('users.index');

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

Route::delete('/slots/{slot}', [SlotController::class, 'destroy'])->name('slots.destroy');

Route::get('/slots/{slot}/edit', [SlotController::class, 'edit'])->name('slots.edit');

Route::put('/slots/{slot}', [SlotController::class, 'update'])->name('slots.update');

// Qui est inscrit sur l'application ? (pour les admins)
Route::get('/users', function () {
    return Inertia::render('Users/Index', [
        'users' => \App\Models\User::withCount('slots')->get()
    ]);
})->middleware(['auth', 'admin'])->name('users.index');

Route::middleware(['auth'])->group(function () {

    // Route pour le rôle
    Route::patch('/users/{user}/role', function (Request $request, User $user) {
        $user->update(['role' => $request->role]);
        return back()->with('message', "Rôle de {$user->name} mis à jour !");
    })->name('users.update-role');

    // Route pour supprimer
    Route::delete('/users/{user}', function (User $user) {
        $user->delete();
        return back()->with('message', "Utilisateur supprimé.");
    })->name('users.destroy');
});
