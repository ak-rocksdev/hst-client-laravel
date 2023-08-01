<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'user';
    protected $primaryKey = 'ID_user';
    public $incrementing = true;

    protected $fillable = [
        'ID_user',
        'activation_code',
        'photoFile',
        'registered_date',
        'full_name',
        'nick_name',
        'dateofbirth',
        'email',
        'password',
        'phone',
        'country',
        'city',
        'instagram',
        'facebook',
        'isActive',
        'flag',
        'level',
        'stance',
        'category',
        'lastupdate',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}