<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name', 'desc'
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class,'category_id');
    }
}
