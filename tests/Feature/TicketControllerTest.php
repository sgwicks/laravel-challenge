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

    public function testRouteReturns200(): void
    {
        $response = $this->get('/tickets/open');

        $response->assertStatus(200);
    }

    public function testReturnsExpectedValues(): void
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

    public function testReturnsOnlyOpenTickets(): void
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

    public function testPaginatesResults(): void
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

    public function testSecondPage(): void
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
}
