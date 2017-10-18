<?php

namespace Tests\Unit;

use App\Concert;
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

        $order = $concert->orderTickets('jane@mail.com', 10);

        $this->assertEquals('jane@mail.com', $order->email); 
        $this->assertEquals(10, $order->tickets()->count()); 
    }
}
