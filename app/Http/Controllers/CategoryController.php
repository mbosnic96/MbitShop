<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Brand;
use DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show($id)
    {
        $categories = Category::with(['products', 'children.products'])->findOrFail($id);
        $allCategories = Category::all();
        $brands = Brand::all();
    
        // Getting all products from this category and its children
        $products = $categories->products->merge($categories->children->flatMap(function ($child) {
            return $child->products;
        }));
        $allProducts = Product::all();
        $processors = $allProducts->pluck('processor')->unique();
        $storages = $allProducts->pluck('storage')->unique()->map(fn($value) => (int) $value)->sort();
        $ram_sizes = $allProducts->pluck('ram_size')->unique()->map(fn($value) => (int) $value)->sort();
        $graphics = $allProducts->pluck('graphics_card')->unique();
        $screenSizes = $allProducts->pluck('screen_size')->unique();
    
        return view('categories.show', compact(
            'categories', 'products', 'brands', 'processors', 'storages', 'ram_sizes', 
            'allCategories', 'graphics', 'screenSizes'
        ));
    }

    public function search(Request $request)
    {
        $query = Product::query();
        
        // Filter by Category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
    
        // Filter by Brand (Array of IDs)
        if ($request->filled('brand')) {
            $query->whereIn('brand_id', (array) $request->brand);
        }
    
        // Filter by Processor
        if ($request->filled('processor')) {
            $query->where('processor', 'LIKE', '%' . $request->processor . '%');
        }
    
        // Filter by RAM size
        if ($request->filled('ram')) {
            $ramSize = (int) $request->ram;
            $query->where('ram_size', $ramSize);
        }
    
        // Filter by HDD size
        if ($request->filled('hdd')) {
            $hddSize = (int) $request->hdd;
            $query->where('storage', $hddSize);
        }
    
        // Filter by Graphics Card
        if ($request->filled('graphics_card')) {
            $query->where('graphics_card', 'LIKE', '%' . $request->graphics_card . '%');
        }
    
        // Filter by Price Range
        if ($request->filled('price')) {
            [$min, $max] = explode('-', $request->price);
            $query->whereBetween('price', [(float) $min, (float) $max]);
        }
    
        // Filter by Screen Size Range
        if ($request->filled('screen_size')) {
            [$minScreen, $maxScreen] = explode('-', $request->screen_size);
            $query->whereBetween('screen_size', [(float) $minScreen, (float) $maxScreen]);
        }
    
        // Execute the query and return the results
        $filteredProducts = $query->get();
    
        return response()->json($filteredProducts); // Return filtered products as JSON
    }
    
    

    

    public function index()
    {
      
       
    $categories = Category::orderBy('parent_id')->paginate(10); // Uklonjen groupBy()
        
        return view('categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.add-category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
        ]);

        DB::table('categories')->insert([
            'name' => $request -> name,
            'parent_id' => $request -> parent_id ?? null,
            'position' => $request -> position ?? 0,
        ]);
        
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return response()->json($category);
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $category->update([
            'name' => $request->name,
            'parent_id' => $request -> parent_id ?? null,
            'position' => $request -> position ?? 0,
        ]);
    
        return redirect()->back()->with('message', 'Kategorija uspješno izmjenjena!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        
        return redirect()->back()->with('message', 'Kategorija uspješno obrisana!');
    }
}
