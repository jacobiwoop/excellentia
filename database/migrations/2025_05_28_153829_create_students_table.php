<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nom_prenom');          // Nom et prénom en un champ
            $table->string('matricule')->unique(); // Matricule unique généré
            $table->string('telephone');
            $table->string('email')->unique();
            $table->enum('sexe', ['M', 'F']);
            $table->foreignId('site_id')->constrained()->onDelete('cascade');       // clé étrangère site
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');    // clé étrangère filière
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');  // clé étrangère promotion
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
