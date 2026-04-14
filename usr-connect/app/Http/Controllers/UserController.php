<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateRole(Request $request, User $user)
    {
        // Sécurité : Seul un admin peut changer un rôle
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'role' => 'required|string|in:admin,infirmier,volunteer',
        ]);

        $user->update([
            'role' => $validated['role']
        ]);

        return back();
    }
}
