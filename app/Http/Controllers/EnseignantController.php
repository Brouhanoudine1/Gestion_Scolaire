<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class EnseignantController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        unset($validated['password_confirmation']);

        User::create([
            ...$validated,
            'role' => 'enseignant',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Compte enseignant créé avec succès.');
    }
}
