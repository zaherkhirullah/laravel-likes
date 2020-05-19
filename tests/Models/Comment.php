<?php

namespace Hayrullah\Likes\Test\Models;

use Hayrullah\Likes\Traits\Likable;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Likable;

    protected $table = 'posts';
    protected $guarded = [];
}
