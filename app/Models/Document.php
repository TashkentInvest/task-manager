<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'letter_number',
        'received_date',
        'user_id',
        'document_category_id',
        'ministry_id',
        'status_type'
    ];

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(DocumentFile::class);
    }

    public function ministry()
    {
        return $this->belongsTo(Ministry::class,'ministry_id');
    }
}
