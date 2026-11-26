<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\Vehicle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalVehicles = Vehicle::count();
        $rentedVehicles = Vehicle::where('status', 'rented')->count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $maintenanceVehicles = Vehicle::where('status', 'maintenance')->count();

        $activeBookingsCount = Booking::whereIn('status', ['confirmed', 'rented'])->count();
        $pendingReviewCount = Booking::where('status', 'payment_submitted')->count();
        $newInquiriesCount = Inquiry::where('status', 'new')->count();

        $totalRevenue = Booking::whereIn('status', ['confirmed', 'rented', 'completed'])->sum('total_amount');

        $recentBookings = Booking::with('vehicle')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', [
            'totalVehicles' => $totalVehicles,
            'rentedVehicles' => $rentedVehicles,
            'availableVehicles' => $availableVehicles,
            'maintenanceVehicles' => $maintenanceVehicles,
            'activeBookingsCount' => $activeBookingsCount,
            'pendingReviewCount' => $pendingReviewCount,
            'newInquiriesCount' => $newInquiriesCount,
            'totalRevenue' => $totalRevenue,
            'recentBookings' => $recentBookings,
        ]);
    }
}
