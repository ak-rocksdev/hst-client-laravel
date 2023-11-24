<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Score extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();
            $model->created_by = $user->ID_user;
            $model->created_at = now();
        });
        static::updating(function ($model) {
            $user = Auth::user();
            $model->updated_by = $user->ID_user;
            $model->updated_at = now();
        });
    }

    protected $table = 'score_list';
    protected $primaryKey = 'ID_score';
    public $incrementing = true;

    protected $fillable = [
        'ID_score',
        'ID_contestant',
        'ID_judge',
        'score',
        'ID_games',
        'fixed',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
