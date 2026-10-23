<?php

namespace Tests\Feature;

use App\Models\Affectation;
use App\Models\Classe;
use App\Models\Eleves;
use App\Models\Matieres;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_the_school_dashboard(): void
    {
        $admin = $this->createUser('admin');
        $class = Classe::create([
            'nom' => '6e A',
            'libelle' => 'Sixième',
        ]);
        $student = Eleves::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'id_class' => $class->id,
        ]);
        $subject = Matieres::create([
            'nom' => 'Mathématiques',
            'code' => 'MATH',
        ]);
        $teacher = $this->createUser('enseignant');
        Affectation::create([
            'id_user' => $teacher->id,
            'id_matiere' => $subject->id,
            'id_classe' => $class->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee('Gestion scolaire')
            ->assertSee('Inscrire un élève')
            ->assertSee('Créer un compte enseignant')
            ->assertSee('La saisie se fait depuis le compte de chaque enseignant')
            ->assertSee('Saisir une évaluation')
            ->assertSee('enseignant@example.com')
            ->assertSee('Déconnexion et accès à l’espace enseignant')
            ->assertSee('Aminata Diallo')
            ->assertSee('Mathématiques')
            ->assertSee('6e A');
    }

    public function test_teacher_is_redirected_to_teacher_dashboard(): void
    {
        $user = $this->createUser('enseignant');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('teacher.dashboard'));
    }

    public function test_teacher_cannot_access_administration_pages(): void
    {
        $teacher = $this->createUser('enseignant');

        $this->actingAs($teacher)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_register_a_student_and_assign_an_enseignant(): void
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)
            ->post(route('admin.classes.store'), [
                'nom' => '3e A',
                'libelle' => 'Troisième',
            ])
            ->assertRedirect(route('admin.dashboard'));
        $class = Classe::firstOrFail();

        $this->post(route('admin.subjects.store'), [
            'nom' => 'Sciences',
            'code' => 'SCI',
        ])->assertRedirect(route('admin.dashboard'));
        $subject = Matieres::firstOrFail();

        $this->post(route('admin.teachers.store'), [
            'prenom' => 'Nadia',
            'name' => 'El Amrani',
            'telephone' => '0600000000',
            'email' => 'nadia@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!',
        ])->assertRedirect(route('admin.dashboard'));
        $teacher = User::where('email', 'nadia@example.com')->firstOrFail();

        $this->post(route('admin.assignments.store'), [
            'id_user' => $teacher->id,
            'id_classe' => $class->id,
            'id_matiere' => $subject->id,
        ])->assertRedirect(route('admin.dashboard'));

        $this->post(route('admin.students.store'), [
            'prenom' => 'Yasmine',
            'nom' => 'Bennani',
            'id_class' => $class->id,
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertSame('enseignant', $teacher->role);
        $this->assertDatabaseHas('affectations', [
            'id_user' => $teacher->id,
            'id_classe' => $class->id,
            'id_matiere' => $subject->id,
        ]);
        $this->assertDatabaseHas('eleves', [
            'prenom' => 'Yasmine',
            'nom' => 'Bennani',
            'id_class' => $class->id,
        ]);
    }

    public function test_teacher_can_create_an_evaluation_for_their_assigned_class(): void
    {
        $teacher = $this->createUser('enseignant');
        $class = Classe::create(['nom' => '5e B', 'libelle' => 'Cinquième']);
        $student = Eleves::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'id_class' => $class->id,
        ]);
        $subject = Matieres::create(['nom' => 'Français', 'code' => 'FR']);
        $assignment = Affectation::create([
            'id_user' => $teacher->id,
            'id_matiere' => $subject->id,
            'id_classe' => $class->id,
        ]);

        $this->actingAs($teacher)
            ->get(route('teacher.dashboard'))
            ->assertOk()
            ->assertSee('5e B')
            ->assertSee('Français');
        $this->get(route('teacher.evaluations.create', ['affectation' => $assignment->id]))
            ->assertOk()
            ->assertSee('Aminata Diallo')
            ->assertSee('/ 20');

        $this->actingAs($teacher)
            ->post(route('teacher.evaluations.store'), [
                'id_affectation' => $assignment->id,
                'titre' => 'Contrôle de lecture',
                'date_evaluation' => '2026-10-21',
                'notes' => [$student->id => '16.5'],
            ])
            ->assertRedirect(route('teacher.dashboard'));

        $this->assertDatabaseHas('evaluations', [
            'id_affectation' => $assignment->id,
            'titre' => 'Contrôle de lecture',
            'note_max' => 20,
        ]);
        $this->assertDatabaseHas('notes', [
            'id_evaluation' => 1,
            'id_eleve' => $student->id,
            'id_teacher' => $teacher->id,
            'notes' => 16.5,
        ]);
        $this->get(route('teacher.dashboard'))
            ->assertOk()
            ->assertSee('Contrôle de lecture')
            ->assertSee('16,50');
    }

    public function test_teacher_cannot_enter_grades_for_another_teachers_assignment(): void
    {
        $teacher = $this->createUser('enseignant');
        $otherTeacher = $this->createUser('enseignant', 'other');
        $class = Classe::create(['nom' => '4e A', 'libelle' => 'Quatrième']);
        $student = Eleves::create([
            'nom' => 'Amrani',
            'prenom' => 'Youssef',
            'id_class' => $class->id,
        ]);
        $subject = Matieres::create(['nom' => 'Histoire', 'code' => 'HIST']);
        $assignment = Affectation::create([
            'id_user' => $otherTeacher->id,
            'id_matiere' => $subject->id,
            'id_classe' => $class->id,
        ]);

        $this->actingAs($teacher)
            ->post(route('teacher.evaluations.store'), [
                'id_affectation' => $assignment->id,
                'titre' => 'Devoir',
                'date_evaluation' => '2026-10-21',
                'notes' => [$student->id => '12'],
            ])
            ->assertNotFound();

        $this->assertDatabaseCount('evaluations', 0);
    }

    private function createUser(string $role, string $suffix = ''): User
    {
        return User::create([
            'name' => 'Benali',
            'prenom' => 'Samira',
            'telephone' => '0600000000',
            'email' => $role.$suffix.'@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => $role,
        ]);
    }
}
