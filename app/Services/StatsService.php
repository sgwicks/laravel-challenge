<?php

namespace App\Services;

use App\Models\Ticket;
use DB;

class StatsService
{
  public function calculate(): array
  {
    $tickets = Ticket::all();

    $email = Ticket::join('users', 'users.id', 'user_id')
      ->select('email')
      ->orderBy(DB::raw('count (*)'), 'desc')
      ->groupBy('user_id')
      ->limit(1)
      ->first()
      ->email ?? null; // null check for when there are no tickets

      // dd(Ticket::firstWhere('id', 5));
    return [
      'total_tickets' => $tickets->count(),
      'unprocessed_tickets' => $tickets->where('status', false)->count(),
      'user_most_submitted' => $email,
      'latest_update' => $tickets->sortBy('updated_at')->last()->updated_at->toDateTimeString() ?? null
    ];
  }
}