<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Follow;
use App\FavPlan;
use App\FavSpot;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// userダミーデータ
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

// flowダミーデータ
$factory->define(Follow::class, function (Faker $faker) {
    return [
        'followed_user_id' => NULL,
        'follower_user_id' => NULL,
    ];
});

// favoritePlanダミーデータ
$factory->define(FavPlan::class, function (Faker $faker) {
    return [
        'user_id' => NULL,
        'plan_id' => NULL,
    ];
});

// favoriteSpotダミーデータ
$factory->define(FavSpot::class, function (Faker $faker) {
    return [
      'user_id' => NULL,
      'spot_id' => NULL,
    ];
});
