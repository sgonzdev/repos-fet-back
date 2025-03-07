<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramsTableSeeder extends Seeder
{
    public function run()
    {
        Program::insert([
            ['name' => 'Ingenieria de Software', 'career' => 'SOF'],
            ['name' => 'Ingenieria Ambiental', 'career' => 'AMB'],
            ['name' => 'Ingenieria Electrica', 'career' => 'ELE'],
            ['name' => 'Salud y Seguridad en el Trabajo', 'career' => 'SST'],
            ['name' => 'Ingenieria de Alimentos', 'career' => 'ALI'],
        ]);
    }
}
