<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category_id', 'subcategory_id', 'brand', 'model', 'conditions',
        'authenticity', 'price', 'negotiable', 'tags'
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function post():HasOne
    {
        return $this->hasOne(Post::class);
    }

    public function contactInfo():HasOne
    {
        return $this->hasOne(ContactInfo::class);
    }
}
