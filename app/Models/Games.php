<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    use HasFactory;

    protected $table = 'games';
    protected $primaryKey = 'ID_games';
    public $incrementing = true;

    protected $fillable = [
        'ID_games',
        'ID_type',
        'status',
        'ID_competition'
    ];
}
