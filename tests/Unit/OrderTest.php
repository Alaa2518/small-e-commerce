<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    private $user ;
    public function __construct(){
        $this->user = User::factory()->create();
    }

    /** @test */
    public function test_can_store_an_order()
    {
        $product = Product::factory()->create(['stock_quantity' => 10, 'price' => 100]);

        $data = [
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ];

        $response = $this->actingAs($this->user)->postJson('/api/orders', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['total_amount' => 200]);
        $this->assertDatabaseHas('order_product', ['product_id' => $product->id, 'quantity' => 2]);
    }

    /** @test */
    public function test_validates_order_data_on_store()
    {
        $response = $this->actingAs($this->user)->postJson('/api/orders', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products']);
    }

    /** @test */
    public function test_can_retrieve_orders()
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->user)->getJson('/api/orders');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['*' => ['id', 'total_amount']]]);
    }

    /** @test */
    public function test_can_show_a_specific_order()
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->user)->getJson('/api/orders/' . $order->id);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'total_amount']);
    }
}
