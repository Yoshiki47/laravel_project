<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'company_id' => 3,
        'product_name' => $faker->name(),
        'price' => $faker->numberBetween(),
        'stock' => $faker->numberBetween(),
        'comment' => $faker->realText(),
        'img_path' => $faker->imageUrl(),
    ];
});
