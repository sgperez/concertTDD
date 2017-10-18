<?php

namespace Tests\Unit\Billing;

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FakePaymentGatewayTest extends TestCase
{
    /** @test */
    public function charges_with_a_valid_token_are_successful()
    {
        $paymentGateway = new FakePaymentGateway();
        $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
        $this->assertEquals(2500, $paymentGateway->totalCharges());
    }

    /** @test */
    /** 
     * @expectedException \App\Billing\PaymentFailedException
     *
     * @return void
     */
    public function charges_with_an_invalid_token_payment_fail()
    {
        $paymentGateway = new FakePaymentGateway;
        $paymentGateway->charge(2500, 'invalid-token');
    }
}
