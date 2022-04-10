<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'detail_user_id' => $this->faker->numberBetween(1, 10),
            'level' => $this->faker->randomElement(['SMA', 'SMK', 'S1', 'S2', 'S3']),
            'institution' => $this->faker->company,
            'address' => $this->faker->address,
            'major' => $this->faker->jobTitle,
            'study_field' => $this->faker->jobTitle,
            'graduation_year' => $this->faker->dateTimeBetween('-30 years', 'now')->format('Y-m-d'),
            'gpa' => $this->faker->randomFloat(2, 3, 4),
            'file_url' => $this->faker->url,
        ];
    }
}
