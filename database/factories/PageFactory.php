<?php
$factory->define(App\Page::class, function (Faker\Generator $faker) {

  return [
    'title' => $faker->sentence(5),
    'content' => '<p>' . $faker->paragraph(20) . '</p>',
    'order' => 1
  ];
});
