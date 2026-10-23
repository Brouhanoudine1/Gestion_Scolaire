<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_eleve')->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_mat')->constrained('matieres')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_teacher')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('notes', 5, 2);
            $table->string('decision');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
