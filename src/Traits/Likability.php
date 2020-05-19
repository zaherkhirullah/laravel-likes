<?php

namespace Hayrullah\LaravelLikes\Traits;

use Hayrullah\LaravelLikes\Models\Like;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @license MIT
 * @package Hayrullah/laravel-likes
 *
 * Copyright @2020 Zaher Khirullah
 */
trait Likability
{
    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    /**
     * Return a collection with the User likes Model.
     * The Model needs to have the likablet.
     *
     * @param  $class *** Accepts for example: Post::class or 'App\Post' ****
     *
     * @return Collection
     */
    public function like($class)
    {
        $likes = $this->likes()->where('likable_type', $class)->with('likable')->get();

        return $likes->mapWithKeys(function ($like) {
            if (!isset($like['likable'])) {
                return [];
            }

            return [$like['likable']->id => $like['likable']];
        });
    }

    /**
     * Add the object to the User visits.
     * The Model needs to have the likable.
     *
     * @param object $object
     */
    public function addLike($object)
    {
        $object->addLike($this->id);
    }

    /**
     * Remove the Object from the user visits.
     * The Model needs to have the likable.
     *
     * @param object $object
     */
    public function removeLike($object)
    {
        $object->removeLike($this->id);
    }

    /**
     * Toggle the visits status from this Object from the user visits.
     * The Model needs to have the likable.
     *
     * @param object $object
     */
    public function toggleLike($object)
    {
        $object->toggleLikes($this->id);
    }

    /**
     * Check if the user has visits this Object
     * The Model needs to have the likable.
     *
     * @param object $object
     *
     * @return bool
     */
    public function isLiked($object)
    {
        return $object->isLiked($this->id);
    }

    /**
     * Check if the user has visited this Object
     * The Model needs to have the likable.
     *
     * @param object $object
     *
     * @return bool
     */
    public function hasLiked($object)
    {
        return $object->isLiked($this->id);
    }
}
