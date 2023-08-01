<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $table = 'competition_list';
    protected $primaryKey = 'ID_competition';
    public $incrementing = false;

    protected $fillable = [
        'ID_competition',
        'ID_series',
        'ID_event',
        'sport',
        'currency',
        'cost',
        'max_contestant',
        'registration_type',
        'score_format',
        'qualification',
        'final',
        'final_contestant',
        'system',
        'judge',
        'level',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}
