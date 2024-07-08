<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateDummyTicketTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCreateDummyTicket(): void
    {
        $this->seed(UserSeeder::class);
        // There shouldn't be any tickets in the database when we start this test
        $this->assertDatabaseCount('tickets', 0);

        $this->artisan('app:create-dummy-ticket')->assertExitCode(0);

        $this->assertDatabaseCount('tickets', 1);
    }
}
