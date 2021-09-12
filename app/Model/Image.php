<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
         'path','post_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
