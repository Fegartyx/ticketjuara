<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\BookingTransaction;
use App\Models\Ticket;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function booking(Ticket $ticket)
    {
        dd($ticket);
        return view('front.booking', compact('ticket'));
    }

    public function bookingStore(Ticket $ticket, StoreBookingRequest $request) {
        $validated = $request->validated();

        $totals = $this->bookingService->calculateTotals($ticket->id, $validated['total_participant']);
        $this->bookingService->storeBookingSession($ticket, $validated, $totals);

        return redirect()->route('front.payment');
    }

    public function payment() {
        $data = $this->bookingService->payment();
        return view('front.payment', $data);
    }

    public function paymentStore(StorePaymentRequest $request) {
        $validated = $request->validated();
        $bookingTransactionId = $this->bookingService->paymentStore($validated);

        if (!$bookingTransactionId) {
            return redirect()->route('front.index')->with('error', 'Payment failed');
        }

        return redirect()->route('front.booking_finished', $bookingTransactionId);
    }

    public function bookingFinished(BookingTransaction $bookingTransaction) {
        return view('front.booking_finished', compact('bookingTransaction'));
    }
}
