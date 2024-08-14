<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;


    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function books()
    {
        return $this->belongsTo(Book::class);
    }

    public function toys()
    {
        return $this->belongsTo(Toy::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

 
}
