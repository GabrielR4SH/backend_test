<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Redirect>
 */
class RedirectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'destination_url' => 'https://' . $this->faker->domainName,  // URL HTTPS fake
            'is_active' => true,
            'last_accessed_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
