<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Notification extends Model
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

    protected $table = 'notification';

    protected $fillable = [
        'ID_user_receiver',
        'type',
        'title',
        'description',
        'read_at'
    ];
}
