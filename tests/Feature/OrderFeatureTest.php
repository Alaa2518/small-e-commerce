<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_create_and_retrieve_orders()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->actingAs($user)->postJson('/api/orders', [
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ])->assertStatus(201);

        $this->getJson('/api/orders')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function test_can_show_a_specific_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->getJson('/api/orders/' . $order->id)
            ->assertStatus(200)
            ->assertJson(['id' => $order->id]);
    }
}
