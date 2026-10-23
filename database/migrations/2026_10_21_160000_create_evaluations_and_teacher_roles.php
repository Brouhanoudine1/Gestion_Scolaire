<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });

        DB::table('users')->where('role', 'user')->update(['role' => 'enseignant']);

        Schema::table('affectations', function (Blueprint $table) {
            $table->unique(
                ['id_user', 'id_matiere', 'id_classe'],
                'affectations_teacher_subject_class_unique'
            );
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_affectation')->constrained('affectations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('titre');
            $table->date('date_evaluation');
            $table->decimal('note_max', 5, 2)->default(20);
            $table->timestamps();
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('id_evaluation')
                ->nullable()
                ->after('id')
                ->constrained('evaluations')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unique(['id_evaluation', 'id_eleve'], 'notes_evaluation_student_unique');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropUnique('notes_evaluation_student_unique');
            $table->dropConstrainedForeignId('id_evaluation');
        });

        Schema::dropIfExists('evaluations');

        Schema::table('affectations', function (Blueprint $table) {
            $table->dropUnique('affectations_teacher_subject_class_unique');
        });

        DB::table('users')->where('role', 'enseignant')->update(['role' => 'user']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->change();
        });
    }
};
