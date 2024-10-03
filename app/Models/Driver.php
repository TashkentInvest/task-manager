<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 
    protected $table = 'drivers';

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
