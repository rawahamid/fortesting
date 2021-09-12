<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name', 'desc'
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class,'post_tag');
    }

}
