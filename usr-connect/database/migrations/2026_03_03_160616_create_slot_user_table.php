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
    Schema::create('slot_user', function (Blueprint $table) {
        $table->id();
        // On lie l'ID de l'utilisateur
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // On lie l'ID du créneau
        $table->foreignId('slot_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_user');
    }
};
