<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Projects\Researcher;
use App\Models\Projects\Source;
use App\Models\Projects\Typology;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'start_date',
        'end_date',
        'objective',
        'status',
        'value',
        'source_id',
        'program_id',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function researchers()
    {
        return $this->belongsToMany(Researcher::class, 'project_researcher');
    }
}
