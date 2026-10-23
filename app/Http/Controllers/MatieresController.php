<?php

namespace App\Http\Controllers;

use App\Models\Matieres;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatieresController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:matieres,nom'],
            'code' => ['required', 'string', 'max:255', 'unique:matieres,code'],
        ]);

        Matieres::create($validated);

        return redirect()->route('admin.dashboard')->with('status', 'Matière créée avec succès.');
    }
}
