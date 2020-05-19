<?php

namespace Hayrullah\Likes\Test;

use Hayrullah\Likes\Test\Models\Article;
use Hayrullah\Likes\Test\Models\Comment;
use Hayrullah\Likes\Test\Models\User;

class LikeModelTest extends TestCase
{
    /** @test */
    public function models_can_add_to_likes_with_auth_user()
    {
        $article = Article::first();
        $user = User::first();
        $this->be($user);

        $article->addLike();

        $this->assertDatabaseHas('likes', [
            'user_id'       => $user->id,
            'likeable_id'   => $article->id,
            'likeable_type' => get_class($article)
        ]);

        $this->assertTrue($article->isLiked());
    }

    /** @test */
    public function models_can_remove_from_likes_with_auth_user()
    {
        $article = Article::first();
        $user = User::first();
        $this->be($user);

        $article->removeLike();

        $this->assertDatabaseMissing('likes', [
            'user_id'       => $user->id,
            'likeable_id'   => $article->id,
            'likeable_type' => get_class($article)
        ]);

        $this->assertFalse($article->isLiked());
    }

    /** @test */
    public function models_can_toggle_their_like_status_with_auth_user()
    {
        $article = Article::first();
        $user = User::first();
        $this->be($user);

        $article->toggleLike();

        $this->assertTrue($article->isLiked());

        $article->toggleLike();

        $this->assertFalse($article->isLiked());
    }

    /** @test */
    public function models_can_add_to_likes_without_the_auth_user()
    {
        $post = Comment::first();
        $post->addLike(2);

        $this->assertDatabaseHas('likes', [
            'user_id'       => 2,
            'likeable_id'   => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertTrue($post->isLiked(2));
    }

    /** @test */
    public function models_can_remove_from_likes_without_the_auth_user()
    {
        $post = Comment::first();
        $post->removeLike(2);

        $this->assertDatabaseMissing('likes', [
            'user_id'       => 2,
            'likeable_id'   => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertFalse($post->isLiked(2));
    }

    /** @test */
    public function models_can_toggle_their_like_status_without_the_auth_user()
    {
        $post = Comment::first();
        $post->toggleLike(2);

        $this->assertTrue($post->isLiked(2));

        $post->toggleLike(2);

        $this->assertFalse($post->isLiked(2));
    }

    /** @test */
    public function user_model_can_add_to_likes_other_models()
    {
        $user = User::first();
        $article = Article::first();

        $user->addLike($article);

        $this->assertDatabaseHas('likes', [
            'user_id'       => $user->id,
            'likeable_id'   => $article->id,
            'likeable_type' => get_class($article)
        ]);

        $this->assertTrue($user->hasLiked($article));
    }

    /** @test */
    public function user_model_can_remove_from_likes_another_models()
    {
        $user = User::first();
        $article = Article::first();

        $user->removeLike($article);

        $this->assertDatabaseMissing('likes', [
            'user_id'       => $user->id,
            'likeable_id'   => $article->id,
            'likeable_type' => get_class($article)
        ]);

        $this->assertFalse($user->isLiked($article));
    }

    /** @test */
    public function user_model_can_toggle_his_like_models()
    {
        $user = User::first();
        $article = Article::first();

        $user->toggleLike($article);

        $this->assertTrue($user->hasLiked($article));

        $user->toggleLike($article);

        $this->assertFalse($user->isLiked($article));
    }

    /** @test */
    public function a_user_can_return_his_liked_models()
    {
        $user = User::first();

        $article1 = Article::find(1);
        $article2 = Article::find(2);
        $article3 = Article::find(3);

        $post1 = Comment::find(1);
        $post2 = Comment::find(2);

        $user->addLike($article1);
        $user->addLike($article2);
        $user->addLike($article3);

        $user->addLike($post1);
        $user->addLike($post2);

        $this->assertEquals(3, $user->like(Article::class)->count());
        $this->assertEquals(2, $user->like(Comment::class)->count());

        $user->removeLike($article1);
        $user->removeLike($article2);
        $user->removeLike($article3);

        $user->removeLike($post1);
        $user->removeLike($post2);

        $this->assertEquals(0, $user->like(Article::class)->count());
        $this->assertEquals(0, $user->like(Comment::class)->count());
    }

    /** @test */
    public function a_model_knows_how_many_users_have_liked_him()
    {
        $article = Article::first();

        $article->toggleLike(1);
        $article->toggleLike(2);
        $article->toggleLike(3);

        $this->assertEquals(3, $article->likesCount());

        $article->toggleLike(1);
        $article->toggleLike(2);
        $article->toggleLike(3);

        $this->assertEquals(0, $article->likesCount());
    }

    /** @test */
    public function a_model_knows_which_users_have_liked_him()
    {
        $article = Article::first();

        $article->toggleLike(1);
        $article->toggleLike(2);
        $article->toggleLike(3);

        $this->assertEquals(3, $article->likedBy()->count());

        $article->toggleLike(1);
        $article->toggleLike(2);
        $article->toggleLike(3);

        $this->assertEquals(0, $article->likedBy()->count());
    }

    /** @test */
    public function a_user_not_return_likes_deleteds()
    {
        $user = User::first();

        $article1 = Article::find(1);
        $article2 = Article::find(2);

        $user->addLike($article1);
        $user->addLike($article2);

        $article1->delete();

        $this->assertEquals(1, $user->like(Article::class)->count());
    }

    /** @test */
    public function a_model_delete_likes_on_deleted_observer()
    {
        $user = User::find(1);
        $user2 = User::find(2);

        $article = Article::first();

        $user->addLike($article);
        $user2->addLike($article);

        $this->assertDatabaseHas(
            'likes', [
                'user_id'       => $user->id,
                'likeable_id'   => $article->id,
                'likeable_type' => get_class($article)
            ]
        );

        $this->assertDatabaseHas(
            'likes', [
                'user_id'       => $user2->id,
                'likeable_id'   => $article->id,
                'likeable_type' => get_class($article)
            ]
        );

        $article->delete();

        $this->assertDatabaseMissing(
            'likes', [
                'user_id'       => $user->id,
                'likeable_id'   => $article->id,
                'likeable_type' => get_class($article)
            ]
        );

        $this->assertDatabaseMissing(
            'likes', [
                'user_id'       => $user2->id,
                'likeable_id'   => $article->id,
                'likeable_type' => get_class($article)
            ]
        );
    }
}
