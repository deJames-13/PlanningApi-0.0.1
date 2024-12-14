<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [ 'name' => 'Administrative and Finance Services Division'],
            [ 'name' => 'Higher Education'],
            [ 'name' => 'Basic Arts and Sciences Department', 'type' => 'department'],
            [ 'name' => 'Civil and Allied Department', 'type' => 'department'],
            [ 'name' => 'Mechanical and Allied Department', 'type' => 'department'],
            [ 'name' => 'Electrical and Allied Department', 'type' => 'department'],
            [ 'name' => 'Office of Student Affairs', 'type' => 'department'],
            [ 'name' => 'Research and Extension'],
        ];

        foreach ($departments as $department) {
            \App\Models\Department::create($department);
        }
    }
}
