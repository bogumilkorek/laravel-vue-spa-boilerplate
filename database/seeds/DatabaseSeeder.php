<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
          'email' => 'admin@api.test',
          'password' => bcrypt('secret'),
        ]);

        factory(App\Page::class, 10)->create();
    }
}
