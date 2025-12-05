<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()
            ->hasArticles(10)
            ->create([
                'name' => 'User 1',
                'email' => 'user1@example.com',
            ]);

        User::factory()
            ->hasArticles(15)
            ->create([
                'name' => 'User 2',
                'email' => 'user2@example.com',
            ]);

        User::factory()
            ->hasArticles(25)
            ->create([
                'name' => 'User 3',
                'email' => 'user3@example.com',
            ]);
    }
}
