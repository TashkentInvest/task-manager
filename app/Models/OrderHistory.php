<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderHistory extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 
    use HasFactory;
    protected $table = 'orders_history';
    protected $fillable = [
        'order_id',
        'user_id',
        'task_id',
        'shipped_time',
        'old_status_id',
        'new_status_id'
    ];
}
