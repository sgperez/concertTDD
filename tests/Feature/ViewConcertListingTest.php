<?php

namespace Tests\Feature;

use App\Concert;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewConcertListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_a_published_concert_listing()
    {
        $concert = factory(Concert::class)->states('published')->create([
            'title' => 'The Red Cat',
            'subtitle' => 'Patranias',
            'date' => Carbon::parse('December 13, 2017 8:00pm'),
            'ticket_price' => 3250,
            'venue' => 'Caca',
            'venue_address' => '123 Example St',
            'city' => 'Laraville',
            'state' => 'NSW',
            'postcode' => 2049,
            'additional_info' => 'For more info call 1300 (ticket)',
        ]);

        $response = $this->get('/concerts/' . $concert->id);

        $response->assertSee('The Red Cat');
        $response->assertSee('Patranias');
        $response->assertSee('December 13, 2017');
        $response->assertSee('8:00pm');
        $response->assertSee('32.50');
        $response->assertSee('Caca');
        $response->assertSee('123 Example St');
        $response->assertSee('Laraville');
        $response->assertSee('NSW');
        $response->assertSee('2049');
        $response->assertSee('For more info call 1300 (ticket)');
    }

    /** @test */
    public function user_cannot_view_unpublished_concerts()
    {
        $this->withExceptionHandling();

        $concert = factory('App\Concert')->states('unpublished')->create();

        $response = $this->get('/concerts/' . $concert->id);
        $response->assertStatus(404);

    }

}
