<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestockControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_restock_and_redirects_to_detail(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        $response = $this->post('/restocks', [
            'tanggal' => '2026-07-25',
            'nama_supplier' => 'Supplier ABC',
        ]);

        $response->assertRedirectContains('/restocks/detail/');
        $this->assertDatabaseHas('restocks', [
            'nama_supplier' => 'Supplier ABC',
        ]);
    }
}
