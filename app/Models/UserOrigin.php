<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrigin extends Model
{
    use HasFactory;

    protected $table = 'user_origin';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'country_id',
        'country_name',
        'state_id',
        'state_name',
        'city_id',
        'city_name',
        'indo_province_id',
        'indo_province_name',
        'indo_city_id',
        'indo_city_name'
    ];
}