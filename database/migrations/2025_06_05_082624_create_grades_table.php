<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();

                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
                $table->foreignId('site_id')->constrained()->onDelete('cascade');
                $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade');

                $table->float('interro1')->nullable();
                $table->float('interro2')->nullable();
                $table->float('interro3')->nullable();
                $table->float('devoir')->nullable();
                $table->float('composition')->nullable();

                $table->float('moy_interro')->nullable();
                $table->float('moy_continue')->nullable();
                $table->float('moy_finale')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
