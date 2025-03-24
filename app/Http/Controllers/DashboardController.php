<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;


use App\Services\WeatherService;
class DashboardController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    public function index()
    {
        // Fetch the products and users
      
        $users = User::paginate(10); 
        return view('users.index', compact('users'));
    }

public function showWeather()
{
    $weather = $this->weatherService->getBihacWeather();
        
    return view('dashboard', [
        'weather' => $weather,
        // Add your other dashboard data here
    ]);
}
}
