<?php

namespace App\Jobs;

use App\Mail\OrderConfirmed;
use App\Models\BookingTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmedEmail implements ShouldQueue
{
    use Queueable;
    private BookingTransaction $bookingTransaction;
    /**
     * Create a new job instance.
     */
    public function __construct(BookingTransaction $bookingTransaction)
    {
        $this->bookingTransaction = $bookingTransaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->bookingTransaction->email)
            ->send(new OrderConfirmed($this->bookingTransaction));
    }
}
