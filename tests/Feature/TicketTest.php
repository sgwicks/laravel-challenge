<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use Database\Seeders\UserSeeder;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * I am eternally uncertain about the value of a test like this
     * outside of a Medium article. Let me know your thoughts!
     */
    public function testCreateTicket(): void
    {
        $user = User::factory()->create();

        $ticket = Ticket::factory()->create([
            'subject' => 'Test Ticket',
            'content' => 'This is a test ticket',
            'status' => false,
            'user_id' => $user->id
        ]);

        $this->assertEquals('Test Ticket', $ticket->subject);
        $this->assertEquals('This is a test ticket', $ticket->content);
        $this->assertFalse($ticket->status);
        $this->assertEquals($user->id, $ticket->user_id);
        $this->assertModelExists($ticket);
    }

    public function testTicketFactoryDefaults(): void
    {
        $this->seed(UserSeeder::class);

        $ticket = Ticket::factory()->create();

        $this->assertModelExists($ticket);
        $this->assertIsString($ticket->subject);
        $this->assertIsString($ticket->content);
        $this->assertFalse($ticket->status);
        $this->assertInstanceOf(User::class, $ticket->user);
    }

    public function testCreatingTicketOnUser(): void
    {
        $user = User::factory()->create();

        $ticket = $user->tickets()->create([
            'subject' => 'User Test Ticket',
            'content' => 'Create a ticket from a user'
        ]);

        $this->assertModelExists($ticket);
        $this->assertEquals($user->name, $ticket->user->name);
        $this->assertEquals($user->email, $ticket->user->email);
    }
}
