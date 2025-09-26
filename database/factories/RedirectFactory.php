<?php
namespace Database\Factories;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Factories\Factory;

class RedirectFactory extends Factory
{
    protected $model = Redirect::class;

    public function definition()
    {
        return [
            'destination_url' => $this->faker->url(),
            'is_active' => true,
            'last_accessed_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
