<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            // On ajoute les colonnes manquantes
            if (!Schema::hasColumn('slots', 'category')) {
                $table->string('category')->default('Buvette')->after('description');
            }
            if (!Schema::hasColumn('slots', 'min_volunteers')) {
                $table->integer('min_volunteers')->default(1)->after('end_time');
            }
            // Si max_volunteers existe déjà mais cause souci, on peut le vérifier
            if (!Schema::hasColumn('slots', 'max_volunteers')) {
                $table->integer('max_volunteers')->default(10)->after('min_volunteers');
            }
        });
    }

    public function down(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            $table->dropColumn(['category', 'min_volunteers', 'max_volunteers']);
        });
    }
};
