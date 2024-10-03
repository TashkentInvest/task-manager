<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskStatus extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 
    protected $table = 'task_status';
    const ORDER_ACTIVE = 0;
    const ACTIVE = 1;
    const CANCELED = 2;
    const ACCEPTED = 3;
    const COMPLATED = 4;
    const DELETED = 5;
    const TIME_IS_OVER = 7;
    const TIME_IS_YET_OVER = 77;
    const ADMIN_REJECT = 8;
}
