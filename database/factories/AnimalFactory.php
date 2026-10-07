<?php

namespace Database\Factories;

use App\Enums\Species;
use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Vingt prénoms d'animaux, absents des données du seeder.
     */
    private const NAMES = [
        'Arthur', 'Bella', 'Câline', 'Diesel', 'Filou', 'Gribouille', 'Hercule', 'Iris', 'Jazz', 'Kiwi',
        'Lulu', 'Moka', 'Noisette', 'Olaf', 'Praline', 'Réglisse', 'Saphir', 'Tango', 'Vanille', 'Zorro',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shelter_id' => Shelter::factory(),
            'name' => fake()->randomElement(self::NAMES),
            'species' => fake()->randomElement(Species::cases()),
            'birth_date' => fake()->dateTimeBetween('-10 years', '-2 months'),
            'description' => fake()->optional()->sentence(),
            'adopted_at' => null,
        ];
    }

    /**
     * Un animal déjà adopté, il y a un jour à un an.
     */
    public function adopted(): static
    {
        return $this->state(fn (array $attributes): array => [
            'adopted_at' => fake()->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }
}
