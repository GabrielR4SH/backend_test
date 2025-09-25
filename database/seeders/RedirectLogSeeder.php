<?php

namespace Database\Seeders;

use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Database\Seeder;

class RedirectLogSeeder extends Seeder
{
    public function run(): void
    {
        $redirects = Redirect::all();

        // Logs para o primeiro redirect (google.com)
        foreach (['192.168.1.1', '192.168.1.2', '192.168.1.3'] as $ip) {
            RedirectLog::factory()->create([
                'redirect_id' => $redirects[0]->id,
                'ip_address' => $ip,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'referer' => 'https://site1.com',
                'query_params' => json_encode(['utm_source' => 'facebook']),
                'accessed_at' => now()->subHours(rand(1, 24)),
            ]);
        }

        // Logs para o segundo redirect (facebook.com) com referers variados
        RedirectLog::factory()->create([
            'redirect_id' => $redirects[1]->id,
            'ip_address' => '192.168.1.4',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'referer' => 'https://site2.com',
            'query_params' => json_encode(['utm_source' => 'instagram']),
            'accessed_at' => now()->subDays(2),
        ]);

        RedirectLog::factory(2)->create([
            'redirect_id' => $redirects[1]->id,
            'ip_address' => '192.168.1.5',
            'user_agent' => 'Mozilla/5.0 (Linux; Android 10)',
            'referer' => 'https://site2.com',
            'query_params' => json_encode(['utm_campaign' => 'test']),
            'accessed_at' => now()->subDays(1),
        ]);

        // Logs para os últimos 10 dias (exemplo)
        for ($i = 0; $i < 10; $i++) {
            RedirectLog::factory()->create([
                'redirect_id' => $redirects[3]->id,
                'ip_address' => '192.168.1.6',
                'user_agent' => 'Mozilla/5.0',
                'referer' => 'https://site3.com',
                'query_params' => json_encode([]),
                'accessed_at' => now()->subDays($i),
            ]);
        }
    }
}
