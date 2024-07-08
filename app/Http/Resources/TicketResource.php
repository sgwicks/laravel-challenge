<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subject' => $this->subject,
            'content' => $this->content,
            'status' => (bool)$this->status,
            'created_at' => $this->created_at,
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email
            ]
        ];
    }
}
