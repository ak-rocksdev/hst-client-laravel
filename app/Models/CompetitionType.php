<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionType extends Model
{
    use HasFactory;

    protected $table = 'competition_type';
    protected $primaryKey = 'ID_type';
    public $incrementing = true;

    protected $fillable = [
        'ID_type',
        'name',
        'code'
    ];
}
