<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            // On ajoute le champ min_volunteers
            // .after('description') permet de le placer joliment dans la table
            $table->integer('min_volunteers')->default(10)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            // Si on annule la migration, on supprime la colonne
            $table->dropColumn('min_volunteers');
        });
    }
};