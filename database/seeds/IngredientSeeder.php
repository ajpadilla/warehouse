<?php

use App\Repositories\IngredientRepository;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredientRepository = app(IngredientRepository::class);
        $ingredientsList = ['tomato','lemon','potato','rice','ketchup','lettuce','onion','cheese','meat','chicken'];

        foreach ($ingredientsList as $ingredient) {
            $ingredientRepository->create(['name' => $ingredient, 'quantity' => 5]);
        }
    }
}
