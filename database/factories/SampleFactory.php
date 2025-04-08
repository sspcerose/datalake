<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sample>
 */
class SampleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // Generates a random title with 3 words
            'artist' => $this->faker->name(), // Generates a random artist name
            'genre' => $this->faker->randomElement(['Pop', 'Rock', 'Jazz', 'Classical', 'Hip-Hop', null]), // Random genre or null
            'release_date' => $this->faker->date(),
        ];
    }
}
