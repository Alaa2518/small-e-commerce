<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_can_store_a_product()
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'A great product',
            'price' => 100.00,
            'stock_quantity' => 10,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', $data);
    }

    /** @test */
    public function test_validates_product_data_on_store()
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'price', 'stock_quantity']);
    }

    /** @test */
    public function test_can_retrieve_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['*' => ['id', 'name', 'price', 'stock_quantity']]]);
    }
}
