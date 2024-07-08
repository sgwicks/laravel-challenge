<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function open()
    {
        $tickets = Ticket::where('status', false)->get();

        return TicketResource::collection($tickets);
    }
}
