<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SUPER ADMIN USER
        $superAdmin = User::factory()->create([
            'username' => 'superuser',
            'email' => 'superuser@gmail.com',
            'password' => bcrypt('PlanningSuperUser2024'),
        ]);

        $superAdmin->assignRole('super-admin');

        // ADMIN USER
        $admin = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('PlanningAdmin2024'),
        ]);

        $admin->assignRole('admin');

        // USER USER
        $user = User::factory()->create([
            'username' => 'user',
            'email' => 'example-user@gmail.com',
            'password' => bcrypt('ExampleAdmin2024'),
        ]);

        $user->assignRole('user');

    }
}
