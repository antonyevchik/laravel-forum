<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
//use App\Thread;
use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => $password ?: $password = bcrypt('password'),
        'remember_token' => Str::random(10),
        'confirmed' => true
    ];
});

$factory->state(User::class, 'unconfirmed', function () {
   return [
       'confirmed' => false
   ];
});

$factory->state(User::class, 'administrator', function () {
    return [
        'name' => 'JohnDoe'
    ];
});

$factory->define(App\Thread::class, function ($faker){
    $title = $faker->sentence;

    return [
        'user_id'=>function() {
        return factory('App\User')->create()->id;
        },
        'channel_id'=> function(){
        return factory('App\Channel')->create()->id;
        },
        'title'  => $title,
        'body'   => $faker->paragraph,
        'visits' => 0,
        'slug'   => Str::slug($title),
        'locked' => false,
    ];
});

$factory->define(App\Channel::class, function ($faker){
    $name=$faker->word;
    return [
        'name'=> $name,
        'slug'=> $name
    ];
});

$factory->define(App\Reply::class, function ($faker){
    return [
        'thread_id'=>function() {
            return factory('App\Thread')->create()->id;
        },
        'user_id'=>function() {
            return factory('App\User')->create()->id;
        },
         'body'=> $faker->paragraph
    ];
});

$factory->define(DatabaseNotification::class, function ($faker) {
    return [
        'id' => Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function () {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});
