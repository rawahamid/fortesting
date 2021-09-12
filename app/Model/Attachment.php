<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
         'path','type', 'post_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

}
