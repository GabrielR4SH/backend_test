<?php
namespace Database\Seeders;

use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Database\Seeder;

class RedirectLogSeeder extends Seeder
{
    public function run(): void
    {
        $redirect = Redirect::first();
        if ($redirect) {
            RedirectLog::create([
                'redirect_id' => $redirect->id,
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0',
                'referer' => 'http://example.com',
                'query_params' => json_encode(['utm_source' => 'test']),
                'accessed_at' => now()->subHour(),
            ]);
        }
    }
}
