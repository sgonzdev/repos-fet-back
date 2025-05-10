<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Projects\Typology;

class TypologySeeder extends Seeder
{

    public function run(): void
    {

        $typology = [
            'Generacion de nuevo conocimiento',
            'Desarrollo Tecnologico',
            'Apropiacion social de conocimiento',
            'Formacion de talento humano',
        ];

        foreach ($typology as $typology) {
            Typology::create(['typology' => $typology]);
        }
    }
}
