<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            'Pepperoni',
            'Ham',
            'Sliced mushrooms',
            'Peppers',
            'Onions',
            'Olives',
            'Tomato',
            'Mozzarella cheese',
            'Parmesan cheese',
            'Feta cheese',
            'Chorizo',
            'Sausages',
            'Anchovies',
            'Pineapple',
            'Spinach',
            'Bacon',
            'BBQ Sauce',
            'Tomato Sauce',
            'Basil',
            'Arugula',
            'Egg',
            'Beef',
            'Chicken',
            'Tuna',
            'Corn',
            'Garlic',
            'Oregano',
        ];

        foreach ($ingredients as $ingredientName) {
            Ingredient::create([
                'name' => $ingredientName,
                'price' => mt_rand(1, 30) / 10,
            ]);
        }
    }
}
