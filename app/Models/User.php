<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->isActive = 1;
            $model->created_by = 1;
        });
    }

    // get unread notification where read_at is null and where ID_user is the current user and 'all'
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class, 'ID_user_receiver')->where('read_at', null)->where('ID_user_receiver', $this->ID_user)->orWhere('ID_user_receiver', 'all');
    }

    public function teamManagerApplications()
    {
        return $this->hasMany(TeamManagerApplication::class, 'ID_user');
    }

    public function unapprovedTeamManagerApplications()
    {
        return $this->hasMany(TeamManagerApplication::class, 'ID_user')->where('approval_status', 2);
    }

    public function unrespondedTeamManagerApplications()
    {
        return $this->hasOne(TeamManagerApplication::class, 'ID_user')->where('approval_status', 0);
    }

    public function approvedTeamManagerApplications()
    {
        return $this->hasOne(TeamManagerApplication::class, 'ID_user')->where('approval_status', 1);
    }

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
        'new_password',
        'country_code',
        'phone',
        'country_code_id',
        'city',
        'instagram',
        'facebook',
        'isActive',
        'flag',
        'level',
        'stance',
        'category',
        'password_version',
        'last_login_at',
        'locale',
        'lastupdate',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function getAuthPassword() {
        if ($this->password_version == 1) {
            return $this->new_password;
        }
        return $this->password;
    }
}
