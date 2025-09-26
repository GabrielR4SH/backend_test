<?php
namespace Tests\Feature;

use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Request;

class RedirectFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_with_merge_params()
    {
        Request::macro('ip', fn() => '127.0.0.1');
        Request::macro('userAgent', fn() => 'TestAgent');
        Request::macro('header', fn($key) => $key === 'referer' ? 'http://example.com' : null);

        $redirect = Redirect::factory()->create(['destination_url' => 'https://example.com', 'is_active' => true]);
        $code = $redirect->code;
        $response = $this->get("/r/{$code}?utm_source=facebook");
        $response->assertStatus(302)->assertRedirectContains('https://example.com?utm_source=facebook');
    }

    public function test_stats_calculation()
    {
        $redirect = Redirect::factory()->create(['is_active' => true]);
        $code = $redirect->code;
        RedirectLog::factory()->create(['redirect_id' => $redirect->id]);
        $response = $this->getJson("/api/redirects/{$code}/stats");
        $response->assertStatus(200)->assertJson(['total_accesses' => 1]);
    }

    public function test_stats_no_accesses()
    {
        $redirect = Redirect::factory()->create(['is_active' => true]);
        $code = $redirect->code;
        $response = $this->getJson("/api/redirects/{$code}/stats");
        $response->assertStatus(200)->assertJson(['total_accesses' => 0]);
    }
}
