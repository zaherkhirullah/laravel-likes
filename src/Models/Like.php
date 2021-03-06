<?php

namespace Hayrullah\Likes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Config;

/**
 *
 * @license MIT
 * @package Hayrullah/laravel-likes
 *
 * Copyright @2020 Zaher Khirullah
 */
class Like extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id'];

    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @return MorphTo
     */
    public function likable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(Config::get('auth.providers.users.model'));
    }
}
