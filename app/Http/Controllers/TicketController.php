<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function open()
    {
        $tickets = Ticket::where('status', false)->paginate(10);

        return TicketResource::collection($tickets);
    }

    public function closed()
    {
        $tickets = Ticket::where('status', true)->paginate(10);

        return TicketResource::collection($tickets);
    }
}
