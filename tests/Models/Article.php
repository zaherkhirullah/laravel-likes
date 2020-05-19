<?php

namespace Hayrullah\Likes\Test\Models;

use Hayrullah\Likes\Traits\Likable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Likable;

    protected $table = 'articles';
    protected $guarded = [];
}
