<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramsTableSeeder extends Seeder
{
    public function run()
    {
        $names = [
            'Ingenieria de Software',
            'Ingenieria Ambiental',
            'Ingenieria Electrica',
            'Salud y Seguridad en el Trabajo',
            'Ingenieria de Alimentos',
        ];
        $careers = [
            'SOF',
            'AMB',
            'ELE',
            'SST',
            'ALI',
        ];
        $programs = [];
        foreach ($names as $index => $name) {
            $programs[] = [
                'name' => $name,
                'career' => $careers[$index],
            ];
        }

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
