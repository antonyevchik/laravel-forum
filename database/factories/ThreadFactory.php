<?php

namespace Database\Factories;

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ThreadFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>function() {
                return User::factory()->create()->id;
            },
            'channel_id'=> function(){
                return Channel::factory()->create()->id;
            },
            'title'  => $this->faker->sentence,
            'body'   => $this->faker->paragraph,
            'visits' => 0,
            'slug'   => Str::slug($this->faker->sentence),
            'locked' => false,
        ];
    }
}
