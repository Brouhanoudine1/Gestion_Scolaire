<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Eleves;
use App\Models\Evaluation;
use App\Models\Notes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TeacherDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $assignments = DB::table('affectations')
            ->join('classes', 'classes.id', '=', 'affectations.id_classe')
            ->join('matieres', 'matieres.id', '=', 'affectations.id_matiere')
            ->leftJoin('eleves', 'eleves.id_class', '=', 'classes.id')
            ->where('affectations.id_user', $request->user()->id)
            ->select(
                'affectations.id',
                'classes.nom as classe_nom',
                'matieres.nom as matiere_nom',
                DB::raw('COUNT(DISTINCT eleves.id) as eleves_count')
            )
            ->groupBy('affectations.id', 'classes.nom', 'matieres.nom')
            ->orderBy('classes.nom')
            ->orderBy('matieres.nom')
            ->get();

        $recentEvaluations = Evaluation::query()
            ->join('affectations', 'affectations.id', '=', 'evaluations.id_affectation')
            ->join('classes', 'classes.id', '=', 'affectations.id_classe')
            ->join('matieres', 'matieres.id', '=', 'affectations.id_matiere')
            ->leftJoin('notes', 'notes.id_evaluation', '=', 'evaluations.id')
            ->where('affectations.id_user', $request->user()->id)
            ->select(
                'evaluations.id',
                'evaluations.titre',
                'evaluations.date_evaluation',
                'evaluations.note_max',
                'classes.nom as classe_nom',
                'matieres.nom as matiere_nom',
                DB::raw('COUNT(notes.id) as notes_count'),
                DB::raw('AVG(notes.notes) as moyenne')
            )
            ->groupBy(
                'evaluations.id',
                'evaluations.titre',
                'evaluations.date_evaluation',
                'evaluations.note_max',
                'classes.nom',
                'matieres.nom'
            )
            ->orderByDesc('evaluations.date_evaluation')
            ->limit(10)
            ->get();

        return view('teacher.dashboard', [
            'assignments' => $assignments,
            'recentEvaluations' => $recentEvaluations,
        ]);
    }

    public function createEvaluation(Request $request): View
    {
        $assignment = $this->findAssignmentForTeacher((int) $request->query('affectation'));

        $students = Eleves::query()
            ->where('id_class', $assignment->id_classe)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('teacher.evaluations.create', [
            'assignment' => $assignment,
            'students' => $students,
        ]);
    }

    public function storeEvaluation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_affectation' => ['required', 'integer', 'exists:affectations,id'],
            'titre' => ['required', 'string', 'max:255'],
            'date_evaluation' => ['required', 'date'],
            'notes' => ['required', 'array', 'min:1'],
            'notes.*' => ['required', 'numeric', 'min:0', 'max:20'],
        ]);

        $assignment = $this->findAssignmentForTeacher((int) $validated['id_affectation']);
        $studentIds = Eleves::query()
            ->where('id_class', $assignment->id_classe)
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $submittedStudentIds = array_map('intval', array_keys($validated['notes']));
        sort($studentIds);
        sort($submittedStudentIds);

        if ($studentIds !== $submittedStudentIds) {
            throw ValidationException::withMessages([
                'notes' => 'La liste des élèves a changé. Rechargez la page avant de saisir les notes.',
            ]);
        }

        DB::transaction(function () use ($request, $validated, $assignment): void {
            $evaluation = Evaluation::create([
                'id_affectation' => $assignment->id,
                'titre' => $validated['titre'],
                'date_evaluation' => $validated['date_evaluation'],
                'note_max' => 20,
            ]);

            foreach ($validated['notes'] as $studentId => $score) {
                Notes::create([
                    'id_evaluation' => $evaluation->id,
                    'id_eleve' => $studentId,
                    'id_mat' => $assignment->id_matiere,
                    'id_teacher' => $request->user()->id,
                    'notes' => $score,
                    'decision' => 'Non renseignée',
                ]);
            }
        });

        return redirect()->route('teacher.dashboard')->with('status', 'Évaluation et notes enregistrées.');
    }

    private function findAssignmentForTeacher(int $assignmentId): Affectation
    {
        return Affectation::query()
            ->join('classes', 'classes.id', '=', 'affectations.id_classe')
            ->join('matieres', 'matieres.id', '=', 'affectations.id_matiere')
            ->where('affectations.id', $assignmentId)
            ->where('affectations.id_user', auth()->id())
            ->select(
                'affectations.*',
                'classes.nom as classe_nom',
                'matieres.nom as matiere_nom'
            )
            ->firstOrFail();
    }
}
