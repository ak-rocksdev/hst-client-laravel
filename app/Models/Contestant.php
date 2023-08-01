<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Contestant extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = Auth::user();
            $model->ID_contestant = Contestant::max('ID_contestant') + 1;
            $model->created_by = $user->ID_user;
        });
    }

    protected $table = 'contestant_list';
    protected $primaryKey = 'ID_contestant';
    public $incrementing = false;

    protected $fillable = [
        'ID_contestant',
        'ID_competition',
        'ID_user',
        'attendance',
        'insert_user',
        'insert_date',
        'update_date',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}
