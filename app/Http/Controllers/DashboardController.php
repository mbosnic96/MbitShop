<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        // Fetch the products and users
      
        $users = User::paginate(10); 
        
        // Pass data to the view
        return view('users.index', compact('users'));
    }
}
