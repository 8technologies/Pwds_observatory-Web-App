<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;
    protected $casts = [
    'attachments' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'responsible_person',
        'status',
        'completion_date',
        'milestone_progress',
        'attachments',
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
