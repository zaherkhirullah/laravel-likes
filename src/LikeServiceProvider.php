<?php

namespace Hayrullah\Likes;

use Illuminate\Support\ServiceProvider;

/**
 *
 * @license MIT
 * @package Hayrullah/laravel-likes
 *
 * Copyright @2020 Zaher Khirullah
 */
class LikeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../migrations/create_likes_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_likes_table.php'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
