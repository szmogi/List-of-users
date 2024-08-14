<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;


    protected  $primaryKey = 'id';


    public function comment()
    {

        return $this->hasMany('App\Models\Comment');
    }
}
