<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Redirect;

class RedirectSeeder extends Seeder
{
    public function run()
    {
        Redirect::create([
            'destination_url' => 'https://google.com',
            'is_active' => true,
            'last_accessed_at' => now(),
        ]);

        Redirect::create([
            'destination_url' => 'https://example.com',
            'is_active' => false,
            'last_accessed_at' => now(),
        ]);
    }
}
