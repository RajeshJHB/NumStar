<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Create a sample user and clients for development/testing
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        Client::factory()->count(5)->for($user)->create();
    }
}
