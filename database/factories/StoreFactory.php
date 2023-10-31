<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_ids = User::whereHas('roles', function ($query) {
            $query->where('name','vendor');
        })->pluck('id')->toArray();

        return [
            'user_id' => Arr::random($user_ids),
            'name' => fake()->word(),
            'location' => fake()->address(),
        ];
    }
}
