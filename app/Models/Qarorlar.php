<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qarorlar extends Model
{
    use HasFactory;

    protected $table = 'qarorlars';

    protected $fillable = [
        'user_id',
        'category_id',
        'unique_code',
        'sana',
        'short_name',
        'comment',
        'amount'
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship to the Category model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define the relationship to QarorFile model
    public function files()
    {
        return $this->hasMany(QarorFile::class, 'qaror_id');
    }
}
