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

        return back()->with('success', 'Vous vous êtes désisté avec succès.');
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
        }

        return Inertia::render('Slots/Create');
    }

    // 2. Reçoit les données et les enregistre
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'min_volunteers' => 'required|integer',
            'max_volunteers' => 'required|integer',
        ]);

        Slot::create($validated);

        return redirect()->route('slots.index');
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
