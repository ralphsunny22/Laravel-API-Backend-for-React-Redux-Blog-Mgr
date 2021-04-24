<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getPathAttribute()
    {
        //return asset("post/$this->slug");
        return "post/$this->slug";
    }

    public function getImageUrlAttribute()
    {
        return asset("/images/post/$this->image");
    }
}
