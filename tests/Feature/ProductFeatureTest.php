<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_create_and_retrieve_products()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 100,
            'stock_quantity' => 10,
        ])->assertStatus(201);

        $this->getJson('/api/products')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}

