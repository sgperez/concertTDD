<?php

namespace Tests\Unit;

use App\Concert;
use App\Exceptions\NotEnoughTicketsException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ConcertTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_concert_can_format_its_own_date()
    {
        $concert = factory('App\Concert')->make([
            'date' => Carbon::parse('2017-09-10 10am'),
        ]);

        $this->assertEquals('September 10, 2017', $concert->formatted_date);
    }

    /** @test */
    public function can_get_formatted_start_time()
    {
        $concert = factory('App\Concert')->make([
            'date' => Carbon::parse('2017-09-10 17:00:00'),
        ]);

        $this->assertEquals('5:00pm', $concert->formatted_start_time);
    }

    /** @test */
    public function get_ticket_price_in_dollars()
    {
        $concert = factory('App\Concert')->make([
            'ticket_price' => 3211,
        ]);

        $this->assertEquals('32.11', $concert->ticket_price_in_dollars);
    }

    /** @test */
    public function concerts_with_a_published_at_date_are_published()
    {
        $publishedConcertA = factory('App\Concert')->states('published')->create();
        $publishedConcertB = factory('App\Concert')->states('published')->create();
        $unpublishedConcert = factory('App\Concert')->states('unpublished')->create();

        $publishedConcerts = Concert::published()->get();

        $this->assertTrue($publishedConcerts->contains($publishedConcertA));
        $this->assertTrue($publishedConcerts->contains($publishedConcertB));
        $this->assertFalse($publishedConcerts->contains($unpublishedConcert));
    }

    /** @test */
    public function can_order_concet_tickets()
    {
        $concert = factory('App\Concert')->create();
        $concert->addTickets(10);

        $order = $concert->orderTickets('jane@mail.com', 10);

        $this->assertEquals('jane@mail.com', $order->email); 
        $this->assertEquals(10, $order->tickets()->count()); 
    }

    /** @test */
    public function can_add_tickets()
    {
        $concert = factory('App\Concert')->create();
        $concert->addTickets(50);

        $this->assertEquals(50, $concert->ticketsRemaining());

    }

    /** @test */
    public function tickets_remaining_are_not_associated_with_an_order()
    {
        $concert = factory('App\Concert')->create();
        $concert->addTickets(50);
        $concert->orderTickets('joe@mail.com', 30);

        $this->assertEquals(20, $concert->ticketsRemaining());
    }

    /** @test */
    public function trying_to_buy_more_tickets_than_remain_throws_an_exception()
    {
        $concert = factory('App\Concert')->create();
        $concert->addTickets(10);
        try {
            $concert->orderTickets('joe@mail.com', 11);
        } catch (NotEnoughTicketsException $e) {
            $this->assertNull( $concert->orders()->where('email', 'joe@mail.com')->first() );
            $this->assertEquals(10, $concert->ticketsRemaining());
            return;
        }
        $this->fail("Bought more tickets thank available.");
    }

    /** @test */
    public function cannot_order_tickets_that_have_already_been_purchased()
    {
        $concert = factory('App\Concert')->create();
        $concert->addTickets(10);
        $concert->orderTickets('joe@mail.com', 8);

        try {
            $concert->orderTickets('jane@mail.com', 3);
        } catch (NotEnoughTicketsException $e) {
            $this->assertNull( $concert->orders()->where('email', 'jane@mail.com')->first() );
            $this->assertEquals(2, $concert->ticketsRemaining());
            return;
        }
        $this->fail("Bought more tickets thank available.");

    }
}
