<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public function post()
    {
       return $this->belongsToMany(Post::class,'gallery_post');
    }
}
