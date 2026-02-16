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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignation_id')->constrained()->onDelete('cascade'); // Matière + site + filière + formateur
            $table->enum('type', ['interro', 'devoir', 'compo']);
            $table->string('label')->nullable(); // ex : Interro 1, Devoir 2
            $table->date('date'); // Date de l'évaluation
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
