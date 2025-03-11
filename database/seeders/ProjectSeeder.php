<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Projects\Project;
use App\Models\Projects\Researcher;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'code' => 'PRJ001',
                'name' => 'Proyecto de Investigación 1',
                'status' => 'EN PROCESO',
                'start_date' => now(),
                'end_date' => null,
                'objective' => 'Explorar el impacto de la tecnología en la educación.',
                'source' => 'Universidad',
                'program_id' => 1, // Asegúrate de tener un programa con este ID
                'value' => 50000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PRJ002',
                'name' => 'Proyecto de Desarrollo de Software',
                'status' => 'TERMINADO',
                'start_date' => now()->subYear(),
                'end_date' => now(),
                'objective' => 'Crear un sistema de gestión para estudiantes.',
                'source' => 'Gobierno',
                'program_id' => 2, // Asegúrate de tener un programa con este ID
                'value' => 120000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}