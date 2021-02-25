<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Services\PaymentGateway;
use PHPUnit\Framework\TestCase;

class PaymentGatewayTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCharge()
    {

        // $subject = new PaymentGateway(); 

        // $token = $this->createTestToken;
        // $order = new Order();
        
        // $actual = $subject->charge($token, $order);

        // $charge  = $this->getStripeCharge($actual);

        // $this->assertEqual($charge->total = $order->total);
    }


    private function createTestToken(){

    }

    private function getStripeCharge(string $actual){
        
    }
} 
