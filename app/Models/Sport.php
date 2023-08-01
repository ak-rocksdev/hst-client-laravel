<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;

    protected $table = 'sport';
    protected $primaryKey = 'ID_sport';
    public $incrementing = true;

    protected $fillable = [
        'ID_sport',
        'name',
    ];
}
