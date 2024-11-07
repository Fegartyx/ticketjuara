<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketPhoto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'photo',
        'ticket_id'
    ];
}
