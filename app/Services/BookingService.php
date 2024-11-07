<?php

namespace App\Services;

use App\Models\BookingTransaction;
use App\Repositories\Contracts\IBookingRepository;
use App\Repositories\Contracts\ITicketRepository;
use Illuminate\Support\Facades\DB;

class BookingService
{
    protected ITicketRepository $ticketRepository;
    protected IBookingRepository $bookingRepository;

    public function __construct(ITicketRepository $ticketRepository, IBookingRepository $bookingRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function calculateTotals($ticketId, $totalParticipant): array
    {
        $ppn = 0.11;
        $price = $this->ticketRepository->getPrice($ticketId);

        $subTotal = $price * $totalParticipant;
        $tax = $subTotal * $ppn;
        $total = $subTotal + $tax;

        return [
            'sub_total' => $subTotal,
            'tax' => $tax,
            'total' => $total
        ];
    }

    public function storeBookingSession($ticket,$validatedData,$totals): void
    {
        session()->put('booking', [
            'ticket_id' => $ticket->id,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'total_participant' => $validatedData['total_participant'],
            'started_at' => $validatedData['started_at'],
            'sub_total' => $totals['sub_total'],
            'total_tax' => $totals['tax'],
            'total_amount' => $totals['total']
        ]);
    }

    public function payment() {
        $booking = session('booking');
        $ticket = $this->ticketRepository->find($booking['ticket_id']);

        return compact('booking', 'ticket');
    }

    public function paymentStore(array $validated) {
        $booking = session('booking');
        $bookingTransactionId = null;

        // &$bookingTransactionId is a reference variable jadi data yang null bisa dirubah nilainya
        DB::transaction(function () use ($validated, &$bookingTransactionId, $booking) {
            if (isset($validated['proof'])) {
                // Store proof of payment to storage folder
                $proofPath = $validated['proof']->store('proofs', 'public');
                $validated['proof'] = $proofPath; // menyimpan alamat file ke database /storage/app/public/proofs/namafile.jpg
            }

            $validated['name'] = $booking['name'];
            $validated['email'] = $booking['email'];
            $validated['phone_number'] = $booking['phone_number'];
            $validated['total_participant'] = $booking['total_participant'];
            $validated['started_at'] = $booking['started_at'];
            $validated['total_amount'] = $booking['total_amount'];
            $validated['ticket_id'] = $booking['ticket_id'];
            $validated['is_paid'] = false;
            $validated['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();

            $newBooking = $this->bookingRepository->createBooking($validated);

            $bookingTransactionId = $newBooking->id;
        });

        return $bookingTransactionId;
    }
}
