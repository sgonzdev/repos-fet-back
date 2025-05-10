<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;
    protected $table = 'source';
    protected $fillable = [
        'source', 'typology_id',
    ];

    public function typology()
    {
        return $this->belongsTo(Typology::class, 'typology_id');
    }

}
