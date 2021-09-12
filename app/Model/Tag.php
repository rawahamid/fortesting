<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = [
        'name', 'desc'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class,'post_tag');
    }

}
