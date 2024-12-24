<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(DocumentCategory::class, 'parent_id');
    }

    /**
     * Get the subcategories for this category.
     */
    public function children()
    {
        return $this->hasMany(DocumentCategory::class, 'parent_id');
    }
}
