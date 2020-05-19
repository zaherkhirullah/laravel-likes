# Laravel Likes Management System 


[![Latest Version on Packagist](https://img.shields.io/packagist/v/hayrullah/laravel-likes.svg?style=flat-square)](https://packagist.org/packages/hayrullah/laravel-likes)
[](https://github.com/hayrullah/laravel-likes/workflows/Run%20Tests/badge.svg?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/hayrullah/laravel-likes.svg?style=flat-square)](https://packagist.org/packages/hayrullah/laravel-likes)
![PHP Composer](https://github.com/zaherkhirullah/laravel-likes/workflows/PHP%20Composer/badge.svg?branch=master)
[![GitHub license](https://img.shields.io/github/license/zaherkhirullah/laravel-likes)](https://github.com/zaherkhirullah/laravel-likes)
[![Quality Score](https://img.shields.io/scrutinizer/g/zaherkhirullah/laravel-likes.svg?style=flat-square)](https://scrutinizer-ci.com/g/zaherkhirullah/laravel-likes)
[![`StyleCI](https://styleci.io/repos/265126740/shield)](https://styleci.io/repos/265126740)

### 

<article> </article>

## Documentation, Installation, and Usage Instructions

See the [DOCUMENTATION](https://packagist.org/packages/hayrullah/laravel-likes) for detailed installation and usage instructions.

### INSTALLATION

``` php
$ composer require hayrullah/laravel-likes
 ```

- In Laravel >=5.5 this package will automatically get registered. 
For older versions, update your `config/app.php` by adding an entry for the service provider.

``` php
'providers' => [
    // ...
    Hayrullah\Likes\LikeServiceProvider::class,
];
```

- Publish the database from the command line:

``` shell
php artisan vendor:publish --provider="Hayrullah\Likes\LikeServiceProvider" 
```

- Migrate the database from the command line:

``` shell
php artisan migrate
```

## Models

Your User model should import the `Traits/Likability.php` trait and use it, that trait allows the user to like the models.
(see an example below):

```php
use Hayrullah\Likes\Traits\Likability;

class User extends Authenticatable
{
    use Likability;
}
```

Your models should import the `Traits/Likable.php` trait and use it, that trait have the methods that you'll use to allow the model be likable.
In all the examples I will use the Article model as the model that is 'Likable', thats for example propuses only.
- see an example below:

```php
use Hayrullah\Likes\Traits\Likable;

class Article extends Model
{
    use Likable;
}
```

That's it ... your model is now **"likable"**!
Now the User can like models that have the likable trait.

## Usage

The models can be liked with and without an authenticated user
(see examples below):

### Add to likes and remove from likes:

If no param is passed in the like method, then the model will asume the auth user.

``` php
$article = Article::first();
$article->addLike();    // auth user added to likes this article
$article->removeLike(); // auth user removed from likes this article
$article->toggleLike(); // auth user toggles the like status from this article
```

If a param is passed in the like method, then the model will asume the user with that id.

``` php
$article = Article::first();
$article->addLike(5);    // user with that id added to likes this article
$article->removeLike(5); // user with that id removed from likes this article
$article->toggleLike(5); // user with that id toggles the like status from this article
```

The user model can also add to likes and remove from favrites:

``` php
$user = User::first();
$article = Article::first();
$user->addLike($article);    // The user added to likes this article
$user->removeLike($article); // The user removed from likes this article
$user->toggleLike($article); // The user toggles the like status from this article
```

### Return the like objects for the user:

A user can return the objects he marked as like.
You just need to pass the **class** in the `like()` method in the `User` model.

``` php
$user = Auth::user();
$user->like(Article::class); // returns a collection with the Articles the User marked as like
```

### Return the likes count from an object:

You can return the likes count from an object, you just need to return the `likesCount` attribute from the model

``` php
$article = Article::first();
$article->likesCount; // returns the number of users that have marked as like this object.
```

### Return the users who marked this object as like

You can return the users who marked this object, you just need to call the `likedBy()` method in the object

``` php
$article = Article::first();
$article->likedBy(); // returns a collection with the Users that marked the article as like.
```

### Check if the user already liked an object

You can check if the Auth user have already liked an object, you just need to call the `isLiked()` method in the object

``` php
$article = Article::first();
$article->isLiked(); // returns a boolean with true or false.
```

## Testing

The package have integrated testing, so every time you make a pull request your code will be tested.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.
