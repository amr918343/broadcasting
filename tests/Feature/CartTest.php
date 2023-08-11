<?php

namespace Tests\Feature;

use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Nova\Nova;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_to_cart_with_valid_data()
    {
        // create a user
        $user = factory(User::class)->create();

        // create a product variant
        $productVariant = factory(ProductVariant::class)->create();

        // create a cart request with valid data
        $cartRequestData = [
            'product_id' => $productVariant->product->id,
            'variant_id' => $productVariant->id,
            'logo' => UploadedFile::fake()->image('logo.png'),
            'quantity' => 10,
        ];
        $cartRequest = new CartRequest($cartRequestData);

        // call the addToCart method
        $response = $this->actingAs($user)->postJson('/api/cart', $cartRequestData);

        // assert that the response has a 201 status code
        $response->assertStatus(201);

        // assert that the response message is correct
        $response->assertJson(['message' => 'Product added to cart successfully.']);

        // assert that the cart item was created
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $user->cart->id,
            'product_id' => $productVariant->product->id,
            'variant_id' => $productVariant->id,
            'logo' => 'logo.png',
            'quantity' => 1,
        ]);
    }

    public function test_add_to_cart_with_invalid_data()
    {
        // create a user
        $user = factory(User::class)->create();

        // create a cart request with invalid data
        $cartRequestData = [
            'product_id' => 999, // invalid product_id
            'variant_id' => 999, // invalid variant_id
            'logo' => UploadedFile::fake()->image('logo.gif'), // invalid image type
            'quantity' => 0, // invalid quantity
        ];
        $cartRequest = new CartRequest($cartRequestData);

        // call the addToCart method
        $response = $this->actingAs($user)->postJson('/api/cart', $cartRequestData);

        // assert that the response has a 422 status code
        $response->assertStatus(422);

        // assert that the response message is correct
        $response->assertJson(['message' => 'product is out of stock']);
    }
}
