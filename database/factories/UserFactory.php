<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = User::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'confirmed' => true
        ];
    }

    /**
     * @return UserFactory
     */
    public function unconfirmed()
    {
        return $this->state(function () {
            return [
                'confirmed' => false
            ];
        });
    }

    /**
     * @return UserFactory
     */
    public function administrator()
    {
        return $this->state(function () {
            return [
                'name' => 'JohnDoe'
            ];
        });
    }

}
