<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Post;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\User;

$factory->define(Post::class, function (Faker $faker) {
    $title = $faker->sentence();
    return [
        'title' => $title,
        'slug' => Str::slug($title),
        'body' => $faker->text,
        'user_id' => function () {
            return User::all()->random();
        }
    ];
});
