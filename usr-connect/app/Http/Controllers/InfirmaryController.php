<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InfirmaryController extends Controller
{
    public function index()
    {
        // Sécurité : Si le rôle n'est pas admin ou infirmier, on dégage
        if (!in_array(Auth::user()->role, ['admin', 'infirmier'])) {
            abort(403);
        }

        $slots = Slot::where('category', 'Infirmerie')
            ->where('end_time', '>=', now())
            ->withCount('users')
            ->orderBy('start_time', 'asc')
            ->get();

        return Inertia::render('Infirmary/Index', [
            'slots' => $slots
        ]);
    }

    public function store(Request $request)
    {
        // Sécurité : SEUL l'admin peut créer
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'start_time'     => 'required|date|after_or_equal:now',
            'end_time'       => 'required|date|after:start_time',
            'min_volunteers' => 'required|integer|min:1',
            'max_volunteers' => 'required|integer|min:1|gte:min_volunteers',
        ]);

        $validated['category'] = 'Infirmerie';

        Slot::create($validated);

        return back()->with('message', "Mission d'infirmerie ajoutée !");
    }
}
