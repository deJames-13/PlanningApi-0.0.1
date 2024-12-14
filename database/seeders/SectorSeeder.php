<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            [ 'name'=> "Director's Office" ],
            [ 'name'=> "Planning Office" ],
            [ 'name'=> "Quality Assurance Office" ],
            [ 'name'=> "GAD's Office" ],
            [ 'name'=> "UITC's Office" ],       

            // Department 1
            [ 'department_id'=> 1, 'name'=> "ADAF's Office", ],
            [ 'department_id'=> 1, 'name'=> "HRM Office", ],
            [ 'department_id'=> 1, 'name'=> "Accounting Office", ],
            [ 'department_id'=> 1, 'name'=> "Budget Office", ],
            [ 'department_id'=> 1, 'name'=> "Collecting and Disbursing Office", ],
            [ 'department_id'=> 1, 'name'=> "Records Office", ],
            [ 'department_id'=> 1, 'name'=> "Procurement Office", ],
            [ 'department_id'=> 1, 'name'=> "Property and Supply Office", ],
            [ 'department_id'=> 1, 'name'=> "Infrastructure Development Office", ],
            [ 'department_id'=> 1, 'name'=> "Building and Grounds Maintenance", ],
            [ 'department_id'=> 1, 'name'=> "Electrical Maintenance", ],
            [ 'department_id'=> 1, 'name'=> "HVACR Maintenance", ],
            [ 'department_id'=> 1, 'name'=> "Medical Clinic", ],
            [ 'department_id'=> 1, 'name'=> "Dental Clinic", ],
            [ 'department_id'=> 1, 'name'=> "IGP", ],

            // Department 2
            [ 'department_id'=> 2, 'name' => "ADAA's Office" ],

            // Department 3
            [ 'department_id'=> 3, 'name' => "BTVTE"],

            // Department 4
            [ 'department_id'=> 4, 'name' => "BSES" ],
            [ 'department_id'=> 4, 'name' => "BSCE" ],
            [ 'department_id'=> 4, 'name' => "BETCT" ],
            [ 'department_id'=> 4, 'name' => "BETChET" ],

            // Department 5
            [ 'department_id'=> 5, 'name' => "BSME" ],
            [ 'department_id'=> 5, 'name' => "BETMT" ],
            [ 'department_id'=> 5, 'name' => "BETDMT" ],
            [ 'department_id'=> 5, 'name' => "BETNDT" ],
            [ 'department_id'=> 5, 'name' => "BETEMET" ],
            [ 'department_id'=> 5, 'name' => "BETHVACR" ],
            [ 'department_id'=> 5, 'name' => "BETAT" ],


            // Department 6
            [ 'department_id'=> 6, 'name' => "BSEE" ],
            [ 'department_id'=> 6, 'name' => "BSECE" ],
            [ 'department_id'=> 6, 'name' => "BSIT" ],
            [ 'department_id'=> 6, 'name' => "BETET" ],
            [ 'department_id'=> 6, 'name' => "BETELEX" ],
            [ 'department_id'=> 6, 'name' => "BETICT" ],
            [ 'department_id'=> 6, 'name' => "BETMECT" ],


            // Department 7
            [ 'department_id'=> 7, 'name' => "Registrar's Office" ],
            [ 'department_id'=> 7, 'name' => "Learning Resource Center" ],
            [ 'department_id'=> 7, 'name' => "Guidance Office" ],
            [ 'department_id'=> 7, 'name' => "Sports and Cultural Dev't" ],
            [ 'department_id'=> 7, 'name' => "NSTP/ROTC" ],

            // -
            [ 'name' => "Industry-Based Program" ],
            [ 'name' => "USG" ],
            [ 'name' => "Artisan" ],


            // Department 8
            [ 'department_id'=> 8, 'name' => "ADRE's Office" ],
            [ 'department_id'=> 8, 'name' => "Research Services" ],
            [ 'department_id'=> 8, 'name' => "Extension Services" ],
            
        ];

        foreach ($sectors as $sector) {
            \App\Models\Sector::create($sector);
        }
    }
}
