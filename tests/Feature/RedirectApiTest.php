<?php
namespace Tests\Feature;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_redirect_success()
    {
        $response = $this->postJson('/api/redirects', ['destination_url' => 'https://google.com']);
        $response->assertStatus(201);
    }

    public function test_update_redirect()
    {
        $redirect = Redirect::factory()->create();
        $code = $redirect->code;
        $response = $this->putJson("/api/redirects/{$code}", ['is_active' => false]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('redirects', ['id' => $redirect->id, 'is_active' => 0]);
        $updatedRedirect = Redirect::find($redirect->id);
        $this->assertEquals(false, $updatedRedirect->is_active);
        $this->assertFalse($updatedRedirect->is_active);
    }

    public function test_delete_redirect_soft()
    {
        $redirect = Redirect::factory()->create();
        $code = $redirect->code;
        $response = $this->deleteJson("/api/redirects/{$code}");
        $response->assertStatus(204);
        $this->assertSoftDeleted('redirects', ['id' => $redirect->id]);
    }

    public function test_list_redirects()
    {
        Redirect::factory()->count(3)->create();
        $response = $this->getJson('/api/redirects');
        $response->assertStatus(200)->assertJsonCount(3);
    }
}
