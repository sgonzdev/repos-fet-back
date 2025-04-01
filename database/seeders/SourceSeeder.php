<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Projects\Source;

class SourceSeeder extends Seeder
{

    public function run(): void
    {
        $sources = [
            'Interna',
            'Externa',
            'Mixta',
            'Interna y Externa',
            'Interna y Mixta',
            'Externa y Mixta',
            'Interna, Externa y Mixta',
        ];

        foreach ($sources as $source) {
            Source::create(['source' => $source]);
        }
    }
}
