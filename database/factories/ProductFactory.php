<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    use HasFactory;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2,1,100),
            'ordinal' => $this->faker->randomDigitNotNull,

        ];

    }



}
// $model = Product::class;

// $model->state(Product::class, 'starter', [
//         'id' => Product::STARTER,
//      ]);

//      $model->state(Product::class, 'full', [
//          'id' => Product::FULL,
//      ]);
