<?php

namespace App\Services;

use App\Models\Ticket;
use DB;

class StatsService
{
  public function calculate(): array
  {
    $tickets = Ticket::query()
      ->join('users', 'users.id', 'user_id')
      ->select('status', 'tickets.updated_at', 'email')
      ->get();
  
    $email = $tickets
      ->groupBy('email')
      ->sortDesc()
      ->keys()
      ->first();

    return [
      'total_tickets' => $tickets->count(),
      'unprocessed_tickets' => $tickets->where('status', false)->count(),
      'user_most_submitted' => $email,
      'latest_update' => $tickets->sortBy('updated_at')->last()?->updated_at->toDateTimeString() ?? null
    ];
  }
}