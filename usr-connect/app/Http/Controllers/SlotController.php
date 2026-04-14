<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SlotController extends Controller
{
    /**
     * Liste toutes les missions futures.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Slot::where('end_time', '>=', now());

        // SI l'utilisateur est un simple bénévole, on cache l'infirmerie
        if ($user->role === 'volunteer') {
            $query->where('category', '!=', 'Infirmerie');
        }

        $slots = $query->orderBy('start_time', 'asc')->get();

        return Inertia::render('Slots/Index', [
            'slots' => $slots,
        ]);
    }

    /**
     * Inscription d'un bénévole.
     */
    public function register(Slot $slot)
    {
        /** @var User $user */
        $user = Auth::user();

        // syncWithoutDetaching évite les doublons si on clique deux fois
        $user->slots()->syncWithoutDetaching([$slot->id]);

        $jour = $slot->start_time->translatedFormat('l');

        return back()->with('message', "Inscription validée pour la mission \"{$slot->title}\" ce {$jour} !");
    }

    /**
     * Désinscription d'un bénévole.
     */
    public function unregister(Slot $slot)
    {
        /** @var User $user */
        $user = Auth::user();
        $user->slots()->detach($slot->id);

        return back()->with('message', "Ton désistement pour la mission \"{$slot->title}\" a bien été pris en compte.");
    }

    /**
     * Détails d'une mission.
     */
    public function show(Slot $slot)
    {
        return Inertia::render('Slots/Show', [
            'slot' => $slot->load('users')
        ]);
    }

    /**
     * Missions d'une catégorie spécifique.
     */
    public function category($categoryName)
    {
        $user = Auth::user();

        $slots = Slot::withCount('users')
            ->with('users') // Eager loading pour éviter le problème N+1 (Performance)
            ->where('category', $categoryName)
            ->where('end_time', '>=', now()) // Cohérence avec l'index
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($slot) use ($user) {
                $slot->is_registered = $slot->users->contains($user->id);
                return $slot;
            });

        return Inertia::render('Slots/Category', [
            'slots' => $slots,
            'categoryName' => $categoryName
        ]);
    }

    /**
     * Planning personnel du bénévole connecté.
     */
    public function mySlots()
    {
        /** @var User $user */
        $user = Auth::user();

        $slots = $user->slots()
            ->withCount('users')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($slot) {
                $slot->is_registered = true;
                return $slot;
            });

        return Inertia::render('Slots/MySlots', [
            'slots' => $slots
        ]);
    }

    /**
     * Formulaire de création (Admin uniquement).
     */
    public function create(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('slots.index')->with('error', 'Accès réservé aux administrateurs.');
        }

        return Inertia::render('Slots/Create', [
            'prefilledCategory' => $request->query('category')
        ]);
    }

    /**
     * Enregistrement en base de données.
     */
    public function store(Request $request)
    {
        // Sécurité : Seul l'admin peut enregistrer
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'category'       => 'required|string',
            'start_time'     => 'required|date',
            'end_time'       => 'required|date|after:start_time',
            'min_volunteers' => 'required|integer|min:1',
            'max_volunteers' => 'required|integer|min:1|gte:min_volunteers',
        ]);

        $slot = Slot::create($validated);

        return redirect()->route('slots.index')->with('message', "La mission \"{$slot->title}\" a été créée avec succès !");
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Slot $slot)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return Inertia::render('Slots/Edit', [
            'slot' => $slot,
            'currentCategory' => $slot->category
        ]);
    }

    /**
     * Mise à jour de la mission.
     */
    public function update(Request $request, Slot $slot)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'start_time'     => 'required|date',
            'end_time'       => 'required|date|after:start_time',
            'min_volunteers' => 'required|integer|min:1',
            'max_volunteers' => 'required|integer|min:1|gte:min_volunteers',
            'category' => 'required|string'
        ]);

        $slot->update($validated);

        return redirect()->route('slots.show', $slot->id)->with('message', "La mission \"{$slot->title}\" a été mise à jour !");
    }

    /**
     * Administration des utilisateurs.
     */
    public function users()
    {
        return Inertia::render('Admin/Users', [
            'users' => User::all()
        ]);
    }

    /**
     * Changement de rôle.
     */
    public function updateRole(Request $request, User $user)
    {
        $user->update(['role' => $request->role]);
        return back()->with('message', "Le rôle de {$user->name} a été modifié.");
    }

    /**
     * Suppression d'une mission.
     */
    public function destroy(Slot $slot)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $slot->users()->detach(); // Nettoyage de la table pivot
        $slot->delete();

        return redirect()->route('slots.index')->with('message', "La mission a été supprimée.");
    }

    /**
     * Liste des missions passées.
     */
    public function archives()
    {
        $slots = Slot::withCount('users')
            ->where('end_time', '<', now())
            ->orderBy('start_time', 'desc')
            ->get();

        return Inertia::render('Slots/Archives', [
            'slots' => $slots
        ]);
    }

    /**
     * Génération du PDF.
     */
    public function exportPdf($categoryName)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $slots = Slot::where('category', $categoryName)
            ->withCount('users')
            ->orderBy('start_time', 'asc')
            ->get();

        $data = [
            'category' => $categoryName,
            'slots'    => $slots,
            'date'     => date('d/m/Y'),
        ];

        return Pdf::loadView('pdf.slots', $data)
            ->download("planning-{$categoryName}-solidafoot.pdf");
    }

    public function exportSingleSlotPdf(Slot $slot)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // On charge les utilisateurs inscrits pour cette mission
        $slot->load('users');

        $data = [
            'slot' => $slot,
            'users' => $slot->users,
            'date' => date('d/m/Y à H:i'),
        ];

        // On utilise une vue différente (plus détaillée pour une seule mission)
        return Pdf::loadView('pdf.single-slot', $data)
            ->download("presence-{$slot->title}.pdf");
    }
}
