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
        $user = Auth::user();

        $slots = Slot::withCount('users')
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($slot) use ($user) {
                // On ajoute une propriété 'is_registered' à chaque slot
                $slot->is_registered = $slot->users->contains($user->id);
                return $slot;
            });

        return Inertia::render('Slots/Index', [
            'slots' => $slots,
        ]);
    }
    public function register(Slot $slot)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->slots()->syncWithoutDetaching([$slot->id]);
        $jour = $slot->start_time->translatedFormat('l'); // ex: "samedi"

        return back()->with('message', "Inscription validée pour la mission \"$slot->title\" ce $jour !");
    }

    public function unregister(Slot $slot)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->slots()->detach($slot->id);

        return back()->with('message', "Ton désistement pour la mission \"$slot->title\" a bien été pris en compte.");
    }


    public function show(Slot $slot)
    {
        // On charge les utilisateurs inscrits pour ce créneau précis
        $slot->load('users');

        return Inertia::render('Slots/Show', [
            'slot' => $slot
        ]);
    }


    // 1. Affiche le formulaire
    public function create()
    {
        // Si l'utilisateur n'est pas admin, on le renvoie ailleurs
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('slots.index')->with('error', 'Accès réservé aux administrateurs.');
            return redirect()->route('slots.index')->with('message', "La mission \"$slot->title\" a été créée avec succès.");
        }

        return Inertia::render('Slots/Create');
    }

    // 2. Reçoit les données et les enregistre
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'min_volunteers' => 'required|integer|min:1',
            'max_volunteers' => 'required|integer|min:1|gte:min_volunteers',
        ]);

        Slot::create($validated);

        return redirect()->route('slots.index')->with('success', 'Mission créée !');
    }

    // 3. Affiche le formulaire de modification
    public function edit(Slot $slot)
    {
        return Inertia::render('Slots/Edit', ['slot' => $slot]);
    }

    // 4. Reçoit les données modifiées et les enregistre
    public function update(Request $request, Slot $slot)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'min_volunteers' => 'required|integer|min:1',
            'max_volunteers' => 'required|integer|min:1|gte:min_volunteers',
        ]);

        $slot->update($validated);

        return redirect()->route('slots.show', $slot->id)->with('success', 'Mission mise à jour !');
    }


    public function users()
    {
        // On récupère tous les utilisateurs pour pouvoir changer leur rôle
        return Inertia::render('Admin/Users', [
            'users' => \App\Models\User::all()
        ]);
    }

    public function updateRole(Request $request, \App\Models\User $user)
    {
        $user->update(['role' => $request->role]);
        return back();
    }

    public function destroy(Slot $slot)
    {
        // Sécurité : Seul l'admin peut supprimer
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // On retire d'abord tous les inscrits (nettoyage de la table pivot)
        $slot->users()->detach();

        // On supprime la mission
        $slot->delete();

        // On redirige vers l'accueil avec un message
        return redirect()->route('slots.index')->with('message', 'Mission supprimée avec succès.');
    }
}
