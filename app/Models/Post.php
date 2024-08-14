<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected  $primaryKey = 'id';


    public function comment()
    {

        return $this->hasMany('App\Models\Comment');
    }


    public function rating()
    {

        return $this->hasMany('App\Models\Rating');
    }

    public function image()
    {

        return $this->hasMany('App\Models\Image');
    }

    public function link()
    {

        return $this->hasMany('App\Models\Link');
    }


 
}
