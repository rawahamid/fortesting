<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'desc'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class,'category_id');
    }
}
