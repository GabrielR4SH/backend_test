<?php
namespace Tests\Unit;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Hashids\Hashids;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_code_attribute_generation()
    {
        $redirect = Redirect::factory()->create();
        $this->assertNotEmpty($redirect->code);
        $this->assertEquals(7, strlen($redirect->code));
        $hashids = new Hashids(
            config('hashids.connections.main.salt'),
            config('hashids.connections.main.length'),
            config('hashids.connections.main.alphabet')
        );
        $decoded = $hashids->decode($redirect->code);
        $this->assertNotEmpty($decoded, "Decoded value is empty for code: {$redirect->code}");
        $this->assertEquals($redirect->id, $decoded[0]);
    }

    public function test_find_by_code()
    {
        $redirect = Redirect::factory()->create();
        \Log::info("Test find_by_code: Created redirect ID {$redirect->id} with code {$redirect->code}");
        $found = Redirect::findByCode($redirect->code);
        $this->assertNotNull($found, "findByCode returned null for code: {$redirect->code}");
        $this->assertEquals($redirect->id, $found->id);
    }

    public function test_invalid_code_returns_null()
    {
        $this->assertNull(Redirect::findByCode('invalid'));
    }
}
