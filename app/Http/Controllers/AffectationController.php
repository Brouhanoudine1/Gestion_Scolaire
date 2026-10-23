<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AffectationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_user' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'enseignant'),
            ],
            'id_classe' => ['required', 'integer', 'exists:classes,id'],
            'id_matiere' => ['required', 'integer', 'exists:matieres,id'],
        ]);

        $alreadyAssigned = DB::table('affectations')
            ->where('id_user', $validated['id_user'])
            ->where('id_classe', $validated['id_classe'])
            ->where('id_matiere', $validated['id_matiere'])
            ->exists();

        if ($alreadyAssigned) {
            throw ValidationException::withMessages([
                'id_user' => 'Cette affectation existe déjà.',
            ]);
        }

        DB::table('affectations')->insert([
            ...$validated,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Affectation enregistrée avec succès.');
    }
}
