<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RoleTask extends Model
{
    use HasFactory;

    protected $table = 'role_task';

    protected $fillable = [
        'role_id',
        'task_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }
}
