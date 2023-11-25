<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Contestant extends Model
{
    use HasFactory;

    public function scoresOnFinal()
    {
        return $this->hasMany(Score::class, 'ID_contestant', 'ID_contestant')
            ->leftJoin('games', 'games.ID_games', 'score_list.ID_games')
            ->where('games.ID_type', 3);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = Auth::user();
            $model->ID_contestant = Contestant::max('ID_contestant') + 1;
            $model->created_by = $user->ID_user;
        });
        static::updating(function ($model) {
            $user = Auth::user();
            $model->updated_by = $user->ID_user;
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
