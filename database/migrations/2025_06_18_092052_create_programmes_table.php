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
        // database/migrations/xxxx_create_programmes_table.php
        if (!Schema::hasTable('programmes')) {
            Schema::create('programmes', function (Blueprint $table) {
                $table->id();
                $table->date('date_seance');
                $table->time('heure_debut');
                $table->time('heure_fin');
                $table->foreignId('subject_id')->nullable()->constrained(); // Lien optionnel vers les matières existantes
                $table->string('titre_custom')->nullable(); // Intitulé libre
                $table->text('description')->nullable();
                $table->foreignId('formateur_id')->constrained('users');
                $table->foreignId('filiere_id')->constrained();
                $table->enum('recurrence', ['ponctuel', 'hebdomadaire', 'mensuel']);
                $table->date('date_fin_recurrence')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
