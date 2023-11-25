<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Participant extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = Auth::user();
            $model->created_by = $user->ID_user;
        });
    }

    protected $table = 'participant';
    public $incrementing = true;

    protected $fillable = [
        'ID_user',
        'ID_competition',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
