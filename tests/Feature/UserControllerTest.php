<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->seed(UserSeeder::class);
    }
    
    public function testRouteExists(): void
    {
        $response = $this->get('/users/test@example.com/tickets');

        $response->assertStatus(200);
    }

    public function testReturnsUserTickets(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        Ticket::factory(3)->create(['user_id' => $user->id]);

        $response = $this->get('/users/test@example.com/tickets');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 3, fn (AssertableJson $json) =>
                $json->whereAllType([
                    'id' => 'integer',
                    'subject' => 'string',
                    'content' => 'string',
                    'created_at' => 'string',
                    'status' => 'boolean',
                    'user.name' => 'string',
                    'user.email' => 'string'
                ])
            )
            ->etc()
        );
    }

    public function testResultsArePaginated(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        Ticket::factory(30)->create(['user_id' => $user->id]);

        $response = $this->get('/users/test@example.com/tickets');

        $response->assertJson(fn (AssertableJson $json) =>
            $json
                ->has('data', 10)
                ->has('links')
                ->has('meta')
    );
    }
}
