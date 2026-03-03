<?php

namespace App\Http\Controllers;

use App\Models\Slot; // Très important pour parler à la base de données !
use Inertia\Inertia; // Pour qu'il reconnaise Inertia
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pour qu'il reconnaise Auth

class SlotController extends Controller
{
    public function index()
    {
        // "withCount('users')" va ajouter automatiquement une colonne "users_count" à nos objets
        $slots = Slot::with(['users'])->withCount('users')->orderBy('start_time', 'asc')->get();

        return Inertia::render('Slots/Index', [
            'slots' => $slots
        ]);
    }

    public function register(Slot $slot)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->slots()->syncWithoutDetaching([$slot->id]);

        return back();
    }

    public function unregister(Slot $slot)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->slots()->detach($slot->id);

        return back();
    }

    
    public function show(Slot $slot)
    {
        // On charge les utilisateurs inscrits pour ce créneau précis
        $slot->load('users');

        return Inertia::render('Slots/Show', [
            'slot' => $slot
        ]);
    }
}