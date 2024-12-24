<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'type'];

    public function parent()
    {
        return $this->belongsTo(Ministry::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Ministry::class, 'parent_id');
    }
}
