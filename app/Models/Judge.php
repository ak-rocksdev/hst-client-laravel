<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Judge extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->isActive = 1;
            $model->created_by = 1; // NOTE: Should be replaced by Auth->ID_user 
        });
    }

    protected $table = 'judge_list';
    protected $primaryKey = 'ID_judge';
    public $incrementing = true;

    protected $fillable = [
        'ID_judge',
        'ID_competition',
        'ID_user',
        'isHeadJudge',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
