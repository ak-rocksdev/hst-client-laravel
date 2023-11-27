<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class TeamManagerApplication extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();
            $model->created_by = $user->ID_user;
        });
        static::updating(function ($model) {
            $user = Auth::user();
            $model->updated_by = $user->ID_user;
        });
    }

    protected $table = 'team_manager_application';

    protected $fillable = [
        'ID_user',
        'is_agree_with_tnc',
        'tnc_version',
        'person_in_charge_status',
        'user_manage_status',
        'approval_status',
        'notes'
    ];
}
