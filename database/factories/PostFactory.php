<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Plan;
use App\Spot;
use App\Tag;
use App\Image;
use App\Link;
use App\PlanTag;
use App\SpotTag;
use App\SpotComment;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| ポスト関連 Factories
|--------------------------------------------------------------------------
|
| 投稿に関するダミーデータ作成用factory
|
*/


// planダミーデータ
$factory->define(Plan::class, function (Faker $faker) {
    return [
        'user_id' => NULL,
        'plan_title' => $faker->title,
        'main_transportation' => $faker->randomElement($array = ['徒歩', '車', '電車', 'その他']),
        'plan_duration' => Str::random(10),
        'plan_information' => Str::random(10),
        'created_at' => now(),
        'created_at' => now(),
    ];
});

// spotダミーデータ
$factory->define(Spot::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomNumber($nbDigits = 5),
        'plan_id' => NULL,
        'spot_title' => $faker->title,
        'spot_duration' => $faker->randomElement($array = ['0.5時間', '1時間', '1.5時間', '2時間', '2時間以上']),
        'spot_address' => $faker->address,
        'spot_information' => $faker->realText($maxNbChars = 50, $indexSize = 2),
        'spot_day' => $faker->numberBetween($min = 100, $max = 200),
        'created_at' => now(),
        'created_at' => now(),
    ];
});

// imageダミーデータ
$factory->define(Image::class, function (Faker $faker) {
    return [
        'spot_id' => Null,
        'image_path' => $faker->imageUrl($width = 640, $height = 480, $category = 'cats', $randomize = true, $word = null),
    ];
});

// linkダミーデータ
$factory->define(Link::class, function (Faker $faker) {
    return [
        'spot_id' => Null,
        'link_title' => $faker->title,
        'link_url' => $faker->url,
    ];
});

// tagダミーデータ
$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->title,
    ];
});

// spotcommentダミーデータ
$factory->define(SpotComment::class, function (Faker $faker) {
    return [
        'spot_id' => NULL,
        'user_id' => NULL,
        'comment_title' => $faker->title,
        'comment_content' => $faker->realText($maxNbChars = 50, $indexSize = 2),
        'created_at' => now(),
        'created_at' => now(),
    ];
});

// spottagダミーデータ
$factory->define(SpotTag::class, function (Faker $faker) {
    return [
        'spot_id' => NULL,
        'tag_id' => NULL,
    ];
});

// plantagダミーデータ
$factory->define(PlanTag::class, function (Faker $faker) {
    return [
        'plan_id' => NULL,
        'tag_id' => NULL,
    ];
});
