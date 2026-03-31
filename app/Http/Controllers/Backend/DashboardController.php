<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Station;
use App\Models\TransitRoute;
use App\Models\Trip;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuses  = Bus::count();
        $totalStops  = Station::count();
        $totalRoutes = TransitRoute::count();
        $totalTrips  = Trip::count();

        return view('backend.dashboard', [
            'totalBuses'  => $totalBuses,
            'totalStops'  => $totalStops,
            'totalRoutes' => $totalRoutes,
            'totalTrips'  => $totalTrips,
        ]);
    }
}
