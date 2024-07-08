<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class ProcessTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-ticket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes the oldest unprocessed ticket in the database and sets status to true';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // By default first grabs the oldest ticket
        $ticket = Ticket::firstWhere('status', 0);
        $ticket->status = true;
        $ticket->save();
    }
}
