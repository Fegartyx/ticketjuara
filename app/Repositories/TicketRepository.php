<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository implements \App\Repositories\Contracts\ITicketRepository
{

    public function getPopularTickets($limit = 4): \Illuminate\Database\Eloquent\Collection
    {
        return Ticket::query()->where('is_popular',true)->limit($limit)->get();
    }

    public function getAllNewTickets(): \Illuminate\Database\Eloquent\Collection
    {
        return Ticket::query()->with(['category','seller','photos','haveBenefits'])->latest()->get();
    }

    public function find($id)
    {
        return Ticket::query()->find($id);
    }

    public function getPrice($ticketId)
    {
        $ticket = $this->find($ticketId);
        return $ticket ? $ticket->price : 0;
    }
}
