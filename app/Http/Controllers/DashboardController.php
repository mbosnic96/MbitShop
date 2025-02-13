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
        $brands = Brand::paginate(10); 
        $products = Product::paginate(10); 
        $users = User::paginate(10); 
       $categories = Category::orderBy('parent_id', 'asc')
                      ->orderBy('position', 'asc')
                      ->paginate(10);
        
        // Pass data to the view
        return view('dashboard', compact('products', 'users', 'brands', 'categories'));
    }
}
