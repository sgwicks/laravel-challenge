<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class CreateDummyTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-dummy-ticket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a dummy ticket';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Ticket::factory()->create();
    }
}
