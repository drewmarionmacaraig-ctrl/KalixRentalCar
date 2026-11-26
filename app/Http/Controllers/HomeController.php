<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredVehicles = Vehicle::query()
            ->where('status', '!=', 'maintenance')
            ->orderByDesc('is_featured')
            ->orderBy('daily_rate')
            ->take(3)
            ->get();

        return view('home', [
            'featuredVehicles' => $featuredVehicles,
        ]);
    }
}
