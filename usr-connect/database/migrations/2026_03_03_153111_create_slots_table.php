<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Le nom de la mission (ex: Buvette)
            $table->text('description')->nullable(); // Détails
            $table->dateTime('start_time'); // Début
            $table->dateTime('end_time');   // Fin
            $table->integer('max_volunteers')->default(30); // Nb de places
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};