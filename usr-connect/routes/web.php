<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SlotController;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| 1. ROUTES PUBLIQUES (Accès sans connexion)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| 2. ZONE CONNECTÉE (Middleware 'auth' uniquement)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD ---
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return Inertia::render('Dashboard', [
            'totalMissions'   => Slot::where('start_time', '>', now())->count(),
            'myMissionsCount' => $user->slots()->count(),
            'nextMission'     => $user->slots()
                ->where('start_time', '>', now())
                ->orderBy('start_time', 'asc')
                ->first(),
            'totalVolunteers' => User::count(),
        ]);
    })->name('dashboard');

    Route::get('/mes-missions', [SlotController::class, 'mySlots'])->name('slots.my-slots');

    // --- PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- PLANNING & MISSIONS (SLOTS) ---
    Route::get('/slots', [SlotController::class, 'index'])->name('slots.index');
    Route::get('/slots/create', [SlotController::class, 'create'])->name('slots.create');
    Route::post('/slots', [SlotController::class, 'store'])->name('slots.store');
    Route::get('/slots/category/{category}', [SlotController::class, 'category'])->name('slots.category');


    // Inscriptions
    Route::post('/slots/{slot}/register', [SlotController::class, 'register'])->name('slots.register');
    Route::delete('/slots/{slot}/unregister', [SlotController::class, 'unregister'])->name('slots.unregister');

    // Détails, Edition, Update, Delete, Archives, PDF
    Route::get('/slots/{slot}', [SlotController::class, 'show'])->name('slots.show');
    Route::get('/slots/{slot}/edit', [SlotController::class, 'edit'])->name('slots.edit');
    Route::put('/slots/{slot}', [SlotController::class, 'update'])->name('slots.update');
    Route::delete('/slots/{slot}', [SlotController::class, 'destroy'])->name('slots.destroy');
    Route::get('/slots/archives', [SlotController::class, 'archives'])->name('slots.archives');
    Route::get('/slots/pdf/{categoryName}', [SlotController::class, 'exportPdf'])->name('slots.pdf');

    // --- GESTION DES MEMBRES ---
    // On utilise users.index pour tout le monde (filtré dans la vue)
    Route::get('/membres', function () {
        return Inertia::render('Users/Index', [
            'users' => User::withCount('slots')
                ->orderBy('slots_count', 'desc')
                ->get()
        ]);
    })->name('users.index');

    // Actions sur les membres (Rôle et Suppression)
    Route::patch('/users/{user}/role', function (Request $request, User $user) {
        $user->update(['role' => $request->role]);
        return back()->with('message', "Rôle de {$user->name} mis à jour !");
    })->name('users.update-role');

    Route::delete('/users/{user}', function (User $user) {
        $user->delete();
        return back()->with('message', "Utilisateur supprimé.");
    })->name('users.destroy');

    // Route de secours si ton menu appelle encore admin.users
    Route::get('/admin/users', function () {
        return redirect()->route('users.index');
    })->name('admin.users');
});

/*
|--------------------------------------------------------------------------
| 3. AUTHENTIFICATION (Login, Register, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
