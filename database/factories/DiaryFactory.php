<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Diary>
 */
class DiaryFactory extends Factory
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
            'diary_type_id' => $this->faker->numberBetween(1, 4),
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraph,
            'duration_read' => $this->faker->time('H:i'),
            'cover_image' => 'https://picsum.photos/200/300',
        ];
    }
}
