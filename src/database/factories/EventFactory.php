<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'         => $this->faker->sentence,
            'text'          => $this->faker->paragraph,
            'creation_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'creator_id'    => User::factory(),
        ];
    }
}
