<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $validStatuses = ['confirmed', 'rented', 'completed'];

        $totalRevenue = Booking::whereIn('status', $validStatuses)->sum('total_amount');
        $totalBookings = Booking::whereIn('status', $validStatuses)->count();
        $avgDuration = round((float) Booking::whereIn('status', $validStatuses)->avg('total_days'), 1);

        $totalVehicles = max(1, Vehicle::count());
        $rentedVehicles = Vehicle::where('status', 'rented')->count();
        $utilization = round(($rentedVehicles / $totalVehicles) * 100, 1);

        $monthlyRevenue = Booking::whereIn('status', $validStatuses)
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn (Booking $b) => $b->created_at->format('Y-m'))
            ->map(fn ($group, $month) => (object) [
                'month' => $month,
                'revenue' => $group->sum('total_amount'),
                'count' => $group->count(),
            ])
            ->values()
            ->take(6);

        $topVehicles = Vehicle::withCount(['bookings' => fn ($q) => $q->whereIn('status', $validStatuses)])
            ->withSum(['bookings' => fn ($q) => $q->whereIn('status', $validStatuses)], 'total_amount')
            ->orderByDesc('bookings_sum_total_amount')
            ->take(5)
            ->get();

        return view('admin.reports', [
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'avgDuration' => $avgDuration,
            'utilization' => $utilization,
            'monthlyRevenue' => $monthlyRevenue,
            'topVehicles' => $topVehicles,
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $bookings = Booking::with('vehicle')->latest()->get();

        return response()->streamDownload(function () use ($bookings) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Booking Code', 'Renter', 'Contact', 'Vehicle', 'Pickup', 'Return', 'Days', 'Total Amount', 'Status']);
            foreach ($bookings as $b) {
                fputcsv($out, [
                    $b->booking_code,
                    $b->renter_name,
                    $b->contact_no,
                    $b->vehicle?->name,
                    $b->pickup_datetime->format('Y-m-d H:i'),
                    $b->return_datetime->format('Y-m-d H:i'),
                    $b->total_days,
                    $b->total_amount,
                    $b->status,
                ]);
            }
            fclose($out);
        }, 'kalix-bookings-report.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
