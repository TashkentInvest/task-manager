<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OffDay extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 
    protected $table = 'user_offdays';
    protected $fillable = [
        'user_off_day_id',
        'user_id',
        'date',
        'additional_date',
        'type'
    ];

}
