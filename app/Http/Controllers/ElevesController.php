<?php

namespace App\Http\Controllers;

use App\Models\Eleves;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ElevesController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'id_class' => ['required', 'integer', 'exists:classes,id'],
        ]);

        Eleves::create($validated);

        return redirect()->route('admin.dashboard')->with('status', 'Élève inscrit avec succès.');
    }
}
