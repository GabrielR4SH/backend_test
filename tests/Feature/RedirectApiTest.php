<?php  // Testes de API e validações

namespace Tests\Feature;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class RedirectApiTest extends TestCase {
    use RefreshDatabase;

    public function test_create_redirect_success() {
        $response = $this->postJson('/api/redirects', ['destination_url' => 'https://google.com']);
        $response->assertStatus(201);
        $this->assertDatabaseHas('redirects', ['destination_url' => 'https://google.com']);
    }

    public function test_create_invalid_url_no_https() {
        $response = $this->postJson('/api/redirects', ['destination_url' => 'http://google.com']);
        $response->assertStatus(422)->assertJsonValidationErrors('destination_url');
    }

    public function test_create_invalid_url_self_reference() {
        $response = $this->postJson('/api/redirects', ['destination_url' => 'https://' . request()->getHost()]);
        $response->assertStatus(422)->assertJsonValidationErrors('destination_url');
    }

    public function test_create_invalid_url_status_not_200() {
        // Mock Guzzle para status 404
        $this->mock(Client::class, function ($mock) {
            $mock->shouldReceive('head')->andReturn(new Response(404));
        });
        $response = $this->postJson('/api/redirects', ['destination_url' => 'https://invalid.com']);
        $response->assertStatus(422)->assertJsonValidationErrors('destination_url');
    }

    // Outros testes de invalidação: DNS, query vazia (mas query vazia é no redirect, não create)

    public function test_update_redirect() {
        $redirect = Redirect::factory()->create();
        $response = $this->putJson("/api/redirects/{$redirect->code}", ['is_active' => false]);
        $response->assertStatus(200);
        $this->assertFalse($redirect->fresh()->is_active);
    }

    public function test_delete_redirect_soft() {
        $redirect = Redirect::factory()->create();
        $response = $this->deleteJson("/api/redirects/{$redirect->code}");
        $response->assertStatus(204);
        $this->assertSoftDeleted('redirects', ['id' => $redirect->id]);
        $this->assertFalse($redirect->fresh()->is_active);
    }

    public function test_list_redirects() {
        Redirect::factory(3)->create();
        $response = $this->getJson('/api/redirects');
        $response->assertStatus(200)->assertJsonCount(3);
    }
}
