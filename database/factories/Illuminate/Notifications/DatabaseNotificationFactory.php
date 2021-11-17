<?php

namespace Database\Factories\Illuminate\Notifications;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Notifications\DatabaseNotification;
use Ramsey\Uuid\Uuid;

class DatabaseNotificationFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = DatabaseNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'type' => 'App\Notifications\ThreadWasUpdated',
            'notifiable_id' => function () {
                return auth()->id() ?: User::factory()->create()->id;
            },
            'notifiable_type' => 'App\User',
            'data' => ['foo' => 'bar']
        ];
    }
}
