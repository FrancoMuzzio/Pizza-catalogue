<?php

namespace Database\Factories;
use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pizza>
 */
class PizzaFactory extends Factory
{
    protected $model = Pizza::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
        ];
    }

    public function withIngredients()
    {
        return $this->afterCreating(function (Pizza $pizza) {
            $ingredients = Ingredient::inRandomOrder()->take(rand(1, 5))->pluck('id');
            $pizza->ingredients()->attach($ingredients);
        });
    }
}
