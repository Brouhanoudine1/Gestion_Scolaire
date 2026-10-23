<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleves;
use App\Models\Matieres;
use App\Models\Notes;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $recentStudents = Eleves::query()
            ->leftJoin('classes', 'classes.id', '=', 'eleves.id_class')
            ->select('eleves.id', 'eleves.nom', 'eleves.prenom', 'classes.nom as classe_nom', 'eleves.created_at')
            ->orderByDesc('eleves.created_at')
            ->limit(5)
            ->get();

        $classes = Classe::query()
            ->leftJoin('eleves', 'eleves.id_class', '=', 'classes.id')
            ->select(
                'classes.id',
                'classes.nom',
                'classes.libelle',
                DB::raw('COUNT(eleves.id) as eleves_count')
            )
            ->groupBy('classes.id', 'classes.nom', 'classes.libelle')
            ->orderBy('classes.nom')
            ->get();

        $subjects = Matieres::query()
            ->orderBy('nom')
            ->get();

        $recentGrades = Notes::query()
            ->join('eleves', 'eleves.id', '=', 'notes.id_eleve')
            ->join('matieres', 'matieres.id', '=', 'notes.id_mat')
            ->select(
                'notes.id',
                'notes.notes',
                'notes.decision',
                'notes.created_at',
                'eleves.nom as eleve_nom',
                'eleves.prenom as eleve_prenom',
                'matieres.nom as matiere_nom'
            )
            ->orderByDesc('notes.created_at')
            ->limit(5)
            ->get();

        $recentLessons = DB::table('emplois')
            ->join('affectations', 'affectations.id', '=', 'emplois.id_teacher_assignement')
            ->join('classes', 'classes.id', '=', 'affectations.id_classe')
            ->join('matieres', 'matieres.id', '=', 'affectations.id_matiere')
            ->join('users', 'users.id', '=', 'affectations.id_user')
            ->select(
                'emplois.id',
                'emplois.jour',
                'emplois.heure_de_debut',
                'emplois.heure_fin',
                'classes.nom as classe_nom',
                'matieres.nom as matiere_nom',
                'users.name as enseignant_nom'
            )
            ->orderBy('emplois.jour')
            ->orderBy('emplois.heure_de_debut')
            ->limit(5)
            ->get();

        $teacherAssignments = DB::table('affectations')
            ->join('users', 'users.id', '=', 'affectations.id_user')
            ->join('classes', 'classes.id', '=', 'affectations.id_classe')
            ->join('matieres', 'matieres.id', '=', 'affectations.id_matiere')
            ->leftJoin('eleves', 'eleves.id_class', '=', 'classes.id')
            ->where('users.role', 'enseignant')
            ->select(
                'users.id as teacher_id',
                'users.prenom',
                'users.name',
                'users.email',
                'classes.nom as classe_nom',
                'matieres.nom as matiere_nom',
                DB::raw('COUNT(DISTINCT eleves.id) as eleves_count')
            )
            ->groupBy(
                'users.id',
                'users.prenom',
                'users.name',
                'users.email',
                'classes.nom',
                'matieres.nom'
            )
            ->orderBy('users.prenom')
            ->orderBy('classes.nom')
            ->get();

        return view('dashboard', [
            'studentCount' => Eleves::count(),
            'classCount' => Classe::count(),
            'subjectCount' => Matieres::count(),
            'gradeCount' => Notes::count(),
            'recentStudents' => $recentStudents,
            'classes' => $classes,
            'subjects' => $subjects,
            'recentGrades' => $recentGrades,
            'recentLessons' => $recentLessons,
            'teachers' => User::where('role', 'enseignant')->orderBy('prenom')->orderBy('name')->get(),
            'teacherAssignments' => $teacherAssignments,
        ]);
    }
}
