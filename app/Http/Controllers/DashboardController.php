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
    //weather servis
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    //usercontrol u dasboardu
    public function index()
    {
        $users = User::paginate(10); 
        return view('users.index', compact('users'));
    }
//weather data
public function showWeather()
{
    $weather = $this->weatherService->getBihacWeather();
        
    return view('dashboard', [
        'weather' => $weather,
    ]);
}
}
