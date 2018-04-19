<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Article::class, function (Faker $faker) {
    return [
        'user_id'    => 2,
        'project_id' => 17,
        'title'      => $faker->word,
        'type'       => 'Article Outline',
        'google_id'  => '1wGoZoVCTanz9eeY-VdWBftQIGwGev2d_B8mC02Zzhuw',
    ];
});
