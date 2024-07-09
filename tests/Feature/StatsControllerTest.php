<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StatsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->seed(UserSeeder::class);
    }

    public function testRouteExists(): void
    {
        $response = $this->get('/stats');

        $response->assertStatus(200);
    }

    public function testReturnsTotalTickets(): void
    {
        Ticket::factory(14)->create();

        $response = $this->get('/stats');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('data.total_tickets', 14)->etc()
        );
    }

    public function testReturnsUnprocessedTickets(): void
    {
        Ticket::factory(14)->create();
        Ticket::whereIn('id', [1,2])->each(function (Ticket $ticket) {
            $ticket->status = true;
            $ticket->save();
        });

        $response = $this->get('/stats');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('data.unprocessed_tickets', 12)->etc()
        );
    }

    public function testReturnsMostProlificUser(): void
    {
        $user = User::firstWhere('id', 5);

        Ticket::factory(9)->create(['user_id' => 5]);
        Ticket::factory(7)->create();

        $response = $this->get('/stats');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('data.user_most_submitted', $user->email)->etc()
        );
    }

    public function testReturnsLatestUpdatedTicket(): void
    {
        Ticket::factory(14)->create();
        $ticket = Ticket::firstWhere('id', 5);

        // Gross, but I couldn't think of a better way to test this
        $tomorrow = Carbon::tomorrow();

        $ticket->status = true;
        $ticket->updated_at = $tomorrow;
        $ticket->save();

        $response = $this->get('/stats');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('data.latest_update', $tomorrow->toDateTimeString())->etc()
        );

    }
}
