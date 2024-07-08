<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function tickets(Request $request)
    {
        $tickets = User::firstWhere('email', $request->route('email'))->tickets()->paginate(10);

        return TicketResource::collection($tickets);
    }
}
