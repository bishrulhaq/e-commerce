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

        $this->call(RolesTableSeeder::class);

        $user = User::create([
            'name' => 'Bishrul Haq',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
            'role_id' => 1 // assume 1 is the ID of the admin role
        ]);
    }
}
