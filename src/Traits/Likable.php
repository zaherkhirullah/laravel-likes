<?php

namespace Hayrullah\LaravelLikes\Traits;

use Hayrullah\LaravelLikes\Models\Like;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 *
 * @license MIT
 * @package Hayrullah/laravel-likes
 *
 * Copyright @2020 Zaher Khirullah
 */
trait Likable
{
    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @return MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    /**
     * @param null $user_id (if null its added to the auth user)
     *
     * @return null
     */
    private function getUserId($user_id = null)
    {
        $user_id = ($user_id) ? $user_id : null;
        if (!$user_id) {
            \auth()->check() ? Auth::id() : null;
        }

        return $user_id;
    }
}