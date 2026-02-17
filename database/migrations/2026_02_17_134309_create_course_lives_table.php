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
        Schema::create('course_lives', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('meeting_id')->unique(); // ID Jitsi
            $table->dateTime('date_debut');
            $table->integer('duree_minutes')->nullable();
            $table->boolean('is_active')->default(true);

            // Relations
            $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            // Optionnels pour plus tard
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->onDelete('set null');
            $table->foreignId('site_id')->nullable()->constrained('sites')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lives');
    }
};
