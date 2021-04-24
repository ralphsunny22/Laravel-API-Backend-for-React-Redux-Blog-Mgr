<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Model\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create();
        factory(Post::class, 30)->create();
        // $this->call(UserSeeder::class);
    }
}
