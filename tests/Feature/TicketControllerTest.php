<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void {
        parent::setup();

        $this->seed();
    }

    public function testOpenRouteReturns200(): void
    {
        $response = $this->get('/tickets/open');

        $response->assertStatus(200);
    }

    public function testOpenReturnsExpectedValues(): void
    {
        $response = $this->get('/tickets/open');

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data', 10, fn (AssertableJson $json) =>
                $json
                    ->whereAllType([
                        'id' => 'integer',
                        'subject' => 'string',
                        'content' => 'string',
                        'created_at' => 'string',
                        'status' => 'boolean',
                        'user.name' => 'string',
                        'user.email' => 'string'
                    ])
            )->etc()
        );
    }

    public function testOpenReturnsOnlyOpenTickets(): void
    {
        Ticket::whereIn('id', [1,2])->each(function (Ticket $ticket) {
            $ticket->status = true;
            $ticket->save();
        });

        $response = $this->get('/tickets/open');

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data', 8)->etc()
        );
    }

    public function testOpenPaginatesResults(): void
    {
        Ticket::factory(20)->create();

        $response = $this->get('/tickets/open');

        $response->assertJson(fn (AssertableJson $json) => 
            $json
                ->has('data', 10)
                ->has('links')
                ->has('meta')
        );
    }

    public function testOpenSecondPage(): void
    {
        Ticket::factory(20)->create();

        $response = $this->get('/tickets/open?page=2');

        $response->assertJson(fn (AssertableJson $json) => 
            $json
                ->has('data', 10, fn (AssertableJson $json) =>
                    $json->where('id', 11)->etc()
                )
                ->etc()
        );
    }

    public function testClosedRouteReturns200(): void
    {
        $response = $this->get('/tickets/closed');

        $response->assertStatus(200);
    }

    public function testClosedReturnsExpectedValues(): void
    {
        Ticket::whereIn('id', [1,2,3,4,5,6,7])->each(function (Ticket $ticket) {
            $ticket->status = true;
            $ticket->save();
        });

        $response = $this->get('/tickets/closed');

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data', 7, fn (AssertableJson $json) =>
                $json
                    ->whereAllType([
                        'id' => 'integer',
                        'subject' => 'string',
                        'content' => 'string',
                        'created_at' => 'string',
                        'status' => 'boolean',
                        'user.name' => 'string',
                        'user.email' => 'string'
                    ])
            )->etc()
        );
    }

    public function testClosedReturnsOnlyClosedTickets(): void
    {
        $response = $this->get('/tickets/closed');

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data', 0)->etc()
        );
    }

    public function testClosedPaginatesResults(): void
    {
        Ticket::factory(20)->create(['status' => true]);

        $response = $this->get('/tickets/closed');

        $response->assertJson(fn (AssertableJson $json) => 
            $json
                ->has('data', 10)
                ->has('links')
                ->has('meta')
        );
    }

    public function testClosedSecondPage(): void
    {
        Ticket::factory(20)->create(['status' => true]);

        $response = $this->get('/tickets/closed?page=2');

        $response->assertJson(fn (AssertableJson $json) => 
            $json
                ->has('data', 10, fn (AssertableJson $json) =>
                    $json->where('id', 21)->etc()
                )
                ->etc()
        );
    }
}
