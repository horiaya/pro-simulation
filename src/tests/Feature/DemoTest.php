<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DemoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function test_stripe_webhook_creates_purchase_and_transaction()
    {
        $payload = [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'customer_email' => 'test@example.com',
                    'metadata' => [
                        'user_id' => 1,
                        'item_id' => 6,
                        'payment_method' => 2,
                        'post_code' => '111-2222',
                        'address' => 'テスト市テスト区テスト町',
                        'building_name' => 'テストビル',
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/api/webhook/stripe', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('purchases', [
            'user_id' => 1,
            'item_id' => 6,
            'payment_id' => 2,
            'post_code' => '111-2222',
            'address' => 'テスト市テスト区テスト町',
            'building_name' => 'テストビル',
        ]);

        $this->assertDatabaseHas('transactions', [
            'buyer_id' => 1,
            'item_id' => 6,
            'status' => 'pending',
        ]);
    }
}
