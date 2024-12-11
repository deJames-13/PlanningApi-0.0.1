<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        /* 
        | -----------------------------------------------------
        | ROLEs and PERMISSIONs
        | -----------------------------------------------------
        | Super Admin - USER MANAGEMENT + ALL
        | Admin - ALL with NO USER MANAGEMENT
        | User - CRUD Encoding 
        |    (Budgets(BudgetController), 
        |    Quality Objectives(ObjectiveController), 
        |    BAR1 (BarDataController)) 
        |    Particular (ParticularController)) 
        / -----------------------------------------------------
        / "manage $resource" (view, create, edit, delete, restore) 
         */
        $userOnly = [
            'manage budgets',
            'manage budget-annual',
            'manage objectives',
            'manage bar-data',
            'manage particular',
            'manage particular-value',
        ];
        $adminOnly = array_merge($userOnly, [
            'manage sectors',
            'manage departments',
        ]);
        $allPermissions = array_merge($adminOnly, [
            'manage users',
            'manage roles',
            'manage permissions',
        ]);

        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // SUPER ADMIN
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo($allPermissions);

        // ADMIN
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo($adminOnly);

        // USER
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo($userOnly);

    }
}
