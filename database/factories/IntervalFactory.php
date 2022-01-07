<?php

namespace Database\Factories;

use App\Models\Interval;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntervalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Interval::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startingDate = $this->faker->dateTimeBetween('-10 days', '+0 days');
        $endingDate   = '+' . strtotime(mt_rand(1, 30) . ' minutes', $startingDate->getTimestamp());
        return [
            'patient_id' => 1,
            'note' => $this->faker->jobTitle,
            'started_at' => $startingDate,
            'ended_at' => $endingDate,
        ];
    }
}
