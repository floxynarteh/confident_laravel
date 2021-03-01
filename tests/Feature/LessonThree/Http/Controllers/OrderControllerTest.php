<?php

namespace Tests\Feature\LessonThree\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\PaymentGateway;
// use Newsletter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use App\Exceptions\PaymentGatewayChargeException;
use App\Models\Coupon;
use App\Models\User;
// use Stripe\Order;
use Tests\TestCase;
use Stripe\Error\Card;
use Stripe\Exception;
use Stripe\Exception\CardException;

class OrderControllerTest extends TestCase
{
    use HasFactory;
    use WithFaker, RefreshDatabase;

    /**
     * @test
     */

    public function index_displays_discounted_price_for_coupon(){
        $this->withoutExceptionHandling();
    
        $coupon = Coupon::factory()->create([
             'percent_off' => 10
         ]);

        Product::factory()->create([
            'price' => 10
        ]);

        Product::factory()->create([
            'price' => 20
        ]);

        $response = $this->withSession(['coupon_id' => $coupon->id])->get('/');

        $response->assertOk();
        $response->assertViewIs('orders.index');
        $response->assertViewHasAll(['products','lessons','videos']);

        $response->assertSeeText('Buy Now $10 $9');
        $response->assertSeeText('Buy Now $20 $10');




    }

    /**
     *
     *@test
     */
    public function store_charges_order_and_create_account()
    {
       

        $product = Product::factory()->create();
        $email = $this->faker->safeEmail;
        $token = $this->faker->md5;

        $paymentGateway = $this->mock(PaymentGateway::class);
        $charge_id = $this->faker->md5;
        $paymentGateway->shouldReceive('charge')
           ->with($token, Mockery::type(Order::class))
           ->andReturn('charge-id');

       
        $response = $this->post(route('order.store'), [
            'product_id' => $product->id,
            'stripeToken' => $token,
            'stripeEmail' => $email,
        ]);

        $response->assertRedirect('/users/edit');
        $this->markTestIncomplete();
    }

    /**
     *@test
     * 
     */
    public function store_return_error_view_when_charge_fails(){

        $this->withoutExceptionHandling();
       $product = Product::factory()->create();
       $token = $this->faker->md5;


       $paymentGateway = $this->mock(PaymentGateway::class);
       $exception = new PaymentGatewayChargeException(
           'sad path order exception',
           ['error' => ['data' => 'passed to view']],
       );

       $paymentGateway->shouldReceive('charge')
          ->with($token, Mockery::type(Order::class))
          ->andThrows($exception);

       $response = $this->post(route('order.store'), [
           'product_id' => $product->id,
           'stripeToken' => $token,
           'stripeEmail' => $this->faker->safeEmail,
       ]);
        $response->assertOk();
        $response->assertViewIs('errors.generic');
        $response->assertViewHas('template', 'partials.errors.charge_failed');
        $response->assertViewHas('data', ['data' => 'passed to view']);

   }
}
