<?php

namespace Hayrullah\Likes\Traits;

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
     * Add deleted observer to delete likes registers
     *
     * @return void
     */
    public static function bootLikable()
    {
        static::deleted(function ($model) {
            $model->likes()->delete();
        });
    }

    /**
     * Toggle the like status from this Object
     *
     * @param int $user_id
     */
    public function toggleLike($user_id = null)
    {
        $this->isLiked($user_id) ? $this->removeLike($user_id) : $this->addLike($user_id);
    }

    /**
     * Check if the user has liked this Object
     *
     * @param int $user_id
     *
     * @return boolean
     */
    public function isLiked($user_id = null)
    {
        return $this->likes()->where('user_id', $this->getUserId($user_id))->exists();
    }

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

    /**
     * Remove this Object from the user likes
     *
     * @param int $user_id [if  null its added to the auth user]
     *
     */
    public function removeLike($user_id = null)
    {
        $this->likes()->where('user_id', $this->getUserId($user_id))->delete();
    }

    /**
     * Add this Object to the user likes
     *
     * @param int $user_id
     */
    public function addLike($user_id = null)
    {
        $like = new Like(['user_id' => $this->getUserId($user_id)]);
        $this->likes()->save($like);
    }

    /**
     * Return a collection with the Users who marked as like this Object.
     *
     * @return Collection
     */
    public function likedBy()
    {
        $likes = $this->likes()->with('user')->get();

        return $likes->mapWithKeys(function ($like) {
            return [$like['user']->id => $like['user']];
        });
    }

    /**
     * Count the number of likes
     *
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * @return likesCount attribute
     */
    public function likesCount()
    {
        return $this->likes_count;
    }

}