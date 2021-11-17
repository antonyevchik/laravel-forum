<?php

namespace Database\Factories;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Reply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'thread_id'=>function() {
                return Thread::factory()->create()->id;
            },
            'user_id'=>function() {
                return User::factory()->create()->id;
            },
            'body'=> $this->faker->paragraph
        ];
    }
}
