<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use App\Concert;
use Illuminate\Http\Request;

class ConcertOrdersController extends Controller
{
    private $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store($concertId)
    {
        $this->validate(request(), [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        try {
            $concert = Concert::published()->findOrFail($concertId);
            $ticketQuantity = request('ticket_quantity');

            $this->paymentGateway->charge($ticketQuantity * $concert->ticket_price, request('payment_token'));

            // create order
            $order = $concert->orderTickets(request('email'), $ticketQuantity);
        } catch (PaymentFailedException $e) {
            return response()->json([], 422);
        }

        return response()->json([], 201);
    } 
}
