<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailUser>
 */
class DetailUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = User::factory()->create()->id;
        return [
            'user_id' => $id,
            'name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'birth' => $this->faker->date('Y-m-d'),
            'phone' => $this->faker->numerify('+62###########'),
            'photo' => "https://i.pravatar.cc",
            'address' => $this->faker->city(),
            'job' => $this->faker->jobTitle(),
            'work_address' => $this->faker->address(),
            'practice_place_address' => $this->faker->address(),
            'office_phone_number' => $this->faker->numerify('+62###########'),
            'is_verified' => $this->faker->numberBetween(0, 1),
            'benefits' => 'Consultation Notes, Worksheet, Test Result Sheet, Mental Health Test, Personality Test, Career Interest Test',
            'price' => ($id == 2) ? $this->faker->numberBetween(20000, 50000) : 0,
        ];
    }
}
