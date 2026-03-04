<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // On vérifie si l'utilisateur est admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Si ce n'est pas un admin, on renvoie une erreur 403 (Interdit) 
        // plutôt qu'une redirection qui peut boucler
        abort(403, 'Action non autorisée.');
    }
}
