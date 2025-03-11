<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Projects\Researcher;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'status',
        'start_date',
        'end_date',
        'objective',
        'source',
        'program_id',
        'value'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function researchers()
    {
        return $this->belongsToMany(Researcher::class, 'project_researcher');
    }
}