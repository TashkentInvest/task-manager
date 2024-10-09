<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    use HasFactory;

    protected $table = 'task_user'; // Specify the table name if it's different from convention

    protected $fillable = [
        'task_id', // Add this line
        'user_id', // Also ensure this is included
    ];
}
