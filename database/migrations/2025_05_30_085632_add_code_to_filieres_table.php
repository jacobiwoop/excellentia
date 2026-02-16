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
        Schema::table('filieres', function (Blueprint $table) {
            $table->string('code', 10)->after('nom');
        });
    }
    
    public function down()
    {
        Schema::table('filieres', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
    
};