<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $table = 'score_list';
    protected $primaryKey = 'ID_score';
    public $incrementing = true;

    protected $fillable = [
        'ID_score',
        'ID_contestant',
        'ID_judge',
        'score',
        'ID_games',
        'fixed'
    ];
}
