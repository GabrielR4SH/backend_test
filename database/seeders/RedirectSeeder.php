<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Redirect;

class RedirectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        // Cria 5 redirects fictícios
        Redirect::factory()->create([
            'destination_url' => 'https://google.com',
            'is_active' => true,
            'last_accessed_at' => now()->subHours(2),
        ]);

        Redirect::factory()->create([
            'destination_url' => 'https://facebook.com?utm_campaign=ads',
            'is_active' => true,
            'last_accessed_at' => now()->subDays(1),
        ]);

        Redirect::factory()->create([
            'destination_url' => 'https://twitter.com',
            'is_active' => false,
            'last_accessed_at' => now()->subDays(3),
        ]);

        Redirect::factory()->create([
            'destination_url' => 'https://example.com',
            'is_active' => true,
            'last_accessed_at' => now(),
        ]);

        Redirect::factory()->create([
            'destination_url' => 'https://youtube.com',
            'is_active' => true,
            'last_accessed_at' => now()->subHours(5),
        ]);
    }
}
