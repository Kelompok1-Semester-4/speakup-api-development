<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailCourse>
 */
class DetailCourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'course_id' => $this->faker->numberBetween(1, 10),
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->text,
            'video_link' => $this->faker->url,
            'cover_image' => 'https://picsum.photos/300/300',
            'duration' => $this->faker->time('H:i'),
        ];
    }
}
