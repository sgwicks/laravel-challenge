<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Database\Seeders\TicketSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessTicketTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->seed();
    }

    public function testProcessTicket(): void
    {
        // Check that the first ticket in the DB has a false
        $ticket = Ticket::firstWhere('status', 0);

        $this->artisan('app:process-ticket')->assertExitCode(0);

        $processedTicket = Ticket::firstWhere('id', $ticket->id);

        $this->assertTrue((bool)$processedTicket->status);
    }
}
