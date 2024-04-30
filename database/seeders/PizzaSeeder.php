<?php

namespace Database\Seeders;

use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class PizzaSeeder extends Seeder
{
    const PIZZA_INGREDIENT = [
        'The Fun Pizza' => ['Tomato', 'Sliced mushrooms', 'Feta cheese', 'Sausages', 'Sliced onion', 'Mozzarella cheese', 'Oregano'],
        'The Super Mushroom Pizza' => ['Tomato', 'Bacon', 'Mozzarella cheese', 'Sliced mushrooms', 'Oregano'],
        'The Margarita Pizza' => ['Tomato', 'Basil', 'Mozzarella cheese', 'Oregano'],
        'The Pepperoni Pizza' => ['Tomato', 'Pepperoni', 'Mozzarella cheese', 'Oregano'],
        'The Hawaian Pizza' => ['Tomato', 'Man', 'Mozzarella cheese', 'Oregano', 'Pineapple'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::PIZZA_INGREDIENT as $pizzaName => $ingredients) {
            $pizza = $this->createPizza($pizzaName, $ingredients);
            $this->savePizza($pizza);
        }
    }

    private function createPizza(string $name, array $ingredientNames): Pizza
    {
        $pizza = new Pizza();
        $pizza->name = $name;
        $this->savePizza($pizza);
        $pizza->ingredients()->attach($this->findIngredientIds($ingredientNames));
        return $pizza;
    }

    private function savePizza(Pizza $pizza): void
    {
        $pizza->save();
    }

    private function findIngredientIds(array $ingredientNames): array
    {
        $ingredientIds = [];

        foreach ($ingredientNames as $ingredientName) {
            $ingredient = Ingredient::where('name', $ingredientName)->first();
            if ($ingredient) {
                $ingredientIds[] = $ingredient->id;
            }
        }

        return $ingredientIds;
    }
}
