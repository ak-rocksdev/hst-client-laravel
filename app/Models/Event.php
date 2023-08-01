<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event_list';
    protected $primaryKey = 'ID_event';
    public $incrementing = false;
    
    protected $fillable = [
        'ID_event',
        'name',
        'location',
        'tnc',
        'start_date',
        'end_date',
        'start_registration',
        'end_registration',
        'type',
        'short_link',
        'registration_type',
        'max_join_competition',
        'create_date',
        'create_by',
        'created_at',
        'updated_at'
    ];
}
