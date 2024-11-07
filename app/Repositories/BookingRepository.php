<?php

namespace App\Repositories;

use App\Models\BookingTransaction;
use App\Repositories\Contracts\IBookingRepository;

class BookingRepository implements IBookingRepository
{
    public function createBooking(array $data)
    {
        return BookingTransaction::query()->create($data);
    }

    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber)
    {
        return BookingTransaction::query()->where('booking_trx_id', $bookingTrxId)->where('phone_number',$phoneNumber)->first();
    }
}
