<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin',    'slug' => 'admin'],
            ['name' => 'Manager',  'slug' => 'manager'],
            ['name' => 'Customer', 'slug' => 'customer'],
        ];

        foreach ($roles as $role) {
            // Check if the role (by slug) already exists
            $exists = DB::table('roles')
                ->where('slug', $role['slug'])
                ->exists();

            if (!$exists) {
                DB::table('roles')->insert([
                    'name'       => $role['name'],
                    'slug'       => $role['slug'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
