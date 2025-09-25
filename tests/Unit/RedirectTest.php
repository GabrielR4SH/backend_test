<?php
// Testes unitários para modelos e hashids

namespace Tests\Unit;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class RedirectTest extends TestCase {
    use RefreshDatabase;

    public function test_code_attribute_generation() {
        $redirect = Redirect::factory()->create();
        $code = $redirect->code;
        $this->assertEquals(7, strlen($code));  // Min length
        $this->assertEquals($redirect->id, Hashids::decode($code)[0]);
    }

    public function test_find_by_code() {
        $redirect = Redirect::factory()->create();
        $found = Redirect::findByCode($redirect->code);
        $this->assertEquals($redirect->id, $found->id);
    }

    public function test_invalid_code_returns_null() {
        $this->assertNull(Redirect::findByCode('invalid'));
    }
}
