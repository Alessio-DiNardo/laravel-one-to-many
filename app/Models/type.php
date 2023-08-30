<?php

namespace App\Models;

use App\Models\Admin\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class type extends Model
{
    use HasFactory;

    public function posts()
    {
    return $this->hasMany(Post::class);
}
}


