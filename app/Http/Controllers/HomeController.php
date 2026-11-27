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

        $categories = Vehicle::query()->distinct()->orderBy('category')->pluck('category');

        return view('home', [
            'featuredVehicles' => $featuredVehicles,
            'categories' => $categories,
        ]);
    }
}
