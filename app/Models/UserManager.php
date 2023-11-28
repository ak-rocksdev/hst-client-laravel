<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class UserManager extends Model
{
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = Auth::user();
            $model->created_by = $user->ID_user;
        });
    }

    use HasFactory;

    protected $table = 'user_manager';
    protected $fillable = [
        'ID_user_manager',
        'ID_user_member',
    ];
}
