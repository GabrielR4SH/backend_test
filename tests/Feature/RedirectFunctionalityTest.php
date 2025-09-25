<?php  // Testes de redirecionamento, stats e merge params

namespace Tests\Feature;

use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class RedirectFunctionalityTest extends TestCase {
    use RefreshDatabase;

    public function test_redirect_with_merge_params() {
        $redirect = Redirect::factory()->create(['destination_url' => 'https://example.com?utm_campaign=ads']);
        $code = $redirect->code;

        // Teste juntando origens
        $response = $this->get("/r/{$code}?utm_source=facebook");
        $response->assertStatus(302)->assertRedirect('https://example.com?utm_campaign=ads&utm_source=facebook');

        // Teste priorizando request
        $response = $this->get("/r/{$code}?utm_source=instagram");
        $response->assertStatus(302)->assertRedirect('https://example.com?utm_campaign=ads&utm_source=instagram');

        // Teste ignorando vazio na request
        $response = $this->get("/r/{$code}?utm_source=&utm_campaign=test");
        $response->assertStatus(302)->assertRedirect('https://example.com?utm_campaign=test');
    }

    public function test_stats_calculation() {
        $redirect = Redirect::factory()->create();
        RedirectLog::factory(5)->create(['redirect_id' => $redirect->id, 'ip_address' => '192.168.1.1', 'referer' => 'site1.com']);  // Mesmo IP, unique=1
        RedirectLog::factory(3)->create(['redirect_id' => $redirect->id, 'ip_address' => '192.168.1.2', 'referer' => 'site2.com']);

        // Acessos nos últimos 10 dias
        RedirectLog::factory()->create(['redirect_id' => $redirect->id, 'accessed_at' => now()->subDays(5)]);  // Dentro
        RedirectLog::factory()->create(['redirect_id' => $redirect->id, 'accessed_at' => now()->subDays(11)]);  // Fora

        $response = $this->getJson("/api/redirects/{$redirect->code}/stats");
        $response->assertStatus(200)
            ->assertJsonPath('total_accesses', 10)  // 5+3+1+1 (mas ajuste count real)
            ->assertJsonPath('unique_accesses', 2);  // 2 IPs

        // Verifica top referers
        $this->assertCount(2, $response->json('top_referers'));

        // Verifica últimos 10 dias (não inclui subDays(11))
        $this->assertCount(10, $response->json('last_10_days'));
    }

    public function test_stats_no_accesses() {
        $redirect = Redirect::factory()->create();
        $response = $this->getJson("/api/redirects/{$redirect->code}/stats");
        $response->assertJson([
            'total_accesses' => 0,
            'unique_accesses' => 0,
            'top_referers' => [],
            'last_10_days' => array_fill(0, 10, ['total' => 0, 'unique' => 0]),  // Aprox
        ]);
    }

    //
}
