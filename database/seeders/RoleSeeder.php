<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'description' => 'This is the administrator role.'
        ]);

        Role::create([
            'name' => 'User',
            'description' => 'This is the regular user role.'
        ]);

        Role::create([
            'name' => 'Staff',
            'description' => 'This is the staff role.'
        ]);

        Role::create([
            'name' => 'Trainer',
            'description' => 'This is the trainer role.'
        ]);
    }
}
