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
    
        // Pass the selected category ID to the view
        $selectedCategoryId = $id;
    
        return view('categories.show', compact(
            'categories', 'products', 'brands', 'processors', 'storages', 'ram_sizes', 
            'allCategories', 'graphics', 'screenSizes', 'selectedCategoryId'
        ));
    }

  
        public function search(Request $request)
        {
            // Validate the request
            $request->validate([
                'category' => 'nullable|integer',
                'brand' => 'nullable|array',
                'brand.*' => 'integer',
                'processor' => 'nullable|string',
                'ram' => 'nullable|integer',
                'hdd' => 'nullable|integer',
                'graphics_card' => 'nullable|string',
                'price' => 'nullable|string',
                'screen_size' => 'nullable|array',
                'screen_size.*' => 'numeric',
            ]);
    
            try {
                $query = Product::query();
    
                // Apply filters
                $query->when($request->filled('category'), function ($query) use ($request) {
                    $query->where('category_id', $request->category);
                });
    
                $query->when($request->filled('brand'), function ($query) use ($request) {
                    $query->whereIn('brand_id', $request->brand);
                });
    
                $query->when($request->filled('processor'), function ($query) use ($request) {
                    $query->where('processor', $request->processor);
                });
    
                $query->when($request->filled('ram'), function ($query) use ($request) {
                    $query->where('ram_size', $request->ram);
                });
    
                $query->when($request->filled('hdd'), function ($query) use ($request) {
                    $query->where('storage', $request->hdd);
                });
    
                $query->when($request->filled('graphics_card'), function ($query) use ($request) {
                    $query->where('graphics_card', $request->graphics_card);
                });
    
                $query->when($request->filled('price'), function ($query) use ($request) {
                    $priceRange = explode('-', $request->price);
                    if (count($priceRange) === 2) {
                        [$min, $max] = array_map('floatval', $priceRange);
                        $query->whereBetween('price', [$min, $max]);
                    }
                });
    
                $query->when($request->filled('screen_size'), function ($query) use ($request) {
                    $query->whereIn('screen_size', $request->screen_size);
                });
    
                $filteredProducts = $query->get();
    
                return response()->json($filteredProducts);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                ], 500);
            }
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
