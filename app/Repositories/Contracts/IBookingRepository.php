<?php

namespace App\Repositories\Contracts;

interface IBookingRepository
{
    public function createBooking(array $data);
    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber);
}
