<x-mail::message>
    Hi, {{$booking->name}}, terimakasih telah memesan tiket di website kami, berikut transaction id anda : {{$booking->booking_trx_id}} dan tiket anda sudah kami konfirmasi pembayarannya.

    <x-mail::button :url="route('front.check_booking')">
        Check Booking
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
