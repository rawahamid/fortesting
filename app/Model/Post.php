<?php

namespace App\Model;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'desc','status','user_id','category_id'
    ];

    public static function boot()
    {
        parent::boot();
        parent::addGlobalScope(new UserScope());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'post_tag');
    }

    public function gallery()
    {
        return $this->belongsToMany(Gallery::class,'gallery_post');

    }

    public function images()
    {
        return $this->hasMany(Image::class,'post_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class,'post_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }



}
