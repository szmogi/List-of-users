<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected  $primaryKey = 'id';

    public function data()
    {

        return $this->hasMany('App\Models\Post');
    }

}
