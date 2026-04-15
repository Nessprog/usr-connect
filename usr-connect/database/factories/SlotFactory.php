<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slot>
 */
class SlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'start_time' => now()->addDays(1), // Demain
            'end_time' => now()->addDays(1)->addHours(2), // Demain + 2h
            'category' => 'PÔLE CAISSE', // Une valeur par défaut
            'max_volunteers' => 5,
        ];
    }
}
