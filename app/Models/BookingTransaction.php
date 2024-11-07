<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'booking_trx_id',
        'phone_number',
        'email',
        'proof',
        'total_amount',
        'total_participant',
        'is_paid',
        'started_at',
        'ticket_id'
    ];

    protected $casts = [
        'started_at' => 'date'
    ];

    public static function generateUniqueTrxId(): string
    {
        $prefix = "JRT";
        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while (self::query()->where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
