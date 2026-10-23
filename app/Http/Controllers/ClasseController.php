<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:classes,nom'],
            'libelle' => ['nullable', 'string', 'max:255'],
        ]);

        Classe::create($validated);

        return redirect()->route('admin.dashboard')->with('status', 'Classe créée avec succès.');
    }
}
