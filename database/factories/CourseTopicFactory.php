<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CourseTopic;

class CourseTopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourseTopic::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'course_id' => $this->faker->word(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'datetime' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement(["upcoming","ongoing","completed","cancelled"]),
        ];
    }
}
