<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Model;
use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $categories = Category::all();
        $keys = [];
            foreach ($categories as $category) {
                array_push($keys, $category->id);
            }
        $first = reset($keys);
    $last = end($keys);
    return [
        "title" => $faker->sentence,
        "slug" => $faker->slug,
        "body" => $faker->text(500),
        "categories" => rand($first, $last),
    ];
});
