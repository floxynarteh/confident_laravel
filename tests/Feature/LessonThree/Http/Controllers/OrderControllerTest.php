<?php

namespace Tests\Feature\LessonThree\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\PaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
// use Stripe\Order;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use HasFactory;
    use WithFaker, RefreshDatabase;

    /**
     *
     * @test
     */
    public function test_example()
    {
        $this->withoutExceptionHandling();

        $product = Product::factory()->create();
        $token = $this->faker->md5;

       $paymentGateway = $this->mock(PaymentGateway::class);
       $paymentGateway->shouldReceive('charge')
           ->with($token, Mockery::type(Order::class))
           ->andReturn('charge-id');

        $response = $this->post(route('order.store'), [
            'product_id' => $product->id,
            'stripeToken' => $token,
            'stripeEmail' => $this->faker->safeEmail,
        ]);

        $response->assertRedirect('/users/edit');
    }
}
