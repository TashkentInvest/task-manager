<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    use HasFactory;

    // protected $table = 'task_user';

    // protected $fillable = [
    //     'user_id',
    //     'task_id'
    // ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    // public function task()
    // {
    //     return $this->belongsTo(Tasks::class, 'task_id');
    // }
}
