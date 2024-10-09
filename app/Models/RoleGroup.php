<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleGroup extends Model
{

    use HasFactory;
    protected $table = 'roles';

    // public function tasks()
    // {
    //     return $this->belongsToMany(Tasks::class, 'role_task');
    // }
}
