<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ContractPdfService
{
    public function download(Booking $booking): Response
    {
        $booking->loadMissing(['vehicle', 'addons']);

        $pdf = Pdf::loadView('pdf.contract', [
            'booking' => $booking,
        ])->setPaper('letter', 'portrait');

        return $pdf->download("KLX-Contract-{$booking->booking_code}.pdf");
    }

    public function stream(Booking $booking): Response
    {
        $booking->loadMissing(['vehicle', 'addons']);

        $pdf = Pdf::loadView('pdf.contract', [
            'booking' => $booking,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream("KLX-Contract-{$booking->booking_code}.pdf");
    }
}
