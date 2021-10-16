<?php

namespace Database\Factories\Activities;

use App\Models\Users\User;
use App\Models\Activities\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'log_name' => $this->faker->word,
            'description' => $this->faker->word,
            'subject_type' => Activity::class,
            'subject_id' => $this->faker->randomDigit,
            'causer_type' => User::class,
            'causer_id' => $this->faker->randomDigit,
            'properties' => [
                'attributes' => [
                    'name' => $this->faker->name
                ]
            ]
        ];
    }
}
