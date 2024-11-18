<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QarorFile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'qaror_id',
        'file_path',
    ];

    // Define the relationship to the Qarorlar model
    public function qaror()
    {
        return $this->belongsTo(Qarorlar::class);
    }
}
