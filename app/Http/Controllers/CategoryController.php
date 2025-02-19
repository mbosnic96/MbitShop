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
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail(); // Get category by slug
        $category->load(['products', 'children.products']); // Load relationships
        $allCategories = Category::all();
        $brands = Brand::all();
    
        $products = Product::whereIn('category_id', function ($query) use ($category) {
            $query->select('id')->from('categories')
                  ->where('id', $category->id)
                  ->orWhereIn('id', $category->children->pluck('id'));
        })->paginate(2); // Paginate with 10 products per page
    
        $allProducts = Product::select('processor', 'storage', 'ram_size', 'graphics_card', 'screen_size')->get();
        $processors = $allProducts->pluck('processor')->unique();
        $storages = $allProducts->pluck('storage')->unique()->map(fn($value) => (int) $value)->sort();
        $ram_sizes = $allProducts->pluck('ram_size')->unique()->map(fn($value) => (int) $value)->sort();
        $graphics = $allProducts->pluck('graphics_card')->unique();
        $screenSizes = $allProducts->pluck('screen_size')->unique();
        $selectedCategoryId = $category->id;
    
        return view('categories.show', compact(
            'category', 'products', 'brands', 'processors', 'storages', 'ram_sizes', 
            'allCategories', 'graphics', 'screenSizes', 'selectedCategoryId'
        ));
    }
    

    

    public function search(Request $request)
    {
        $query = Product::query();
    
        // Ensure Category is always applied
        if ($request->filled('category')) {
            $categoryId = $request->category;
            $category = Category::with('children')->find($categoryId);
    
            if ($category) {
                $categoryIds = $category->children->pluck('id')->toArray();
                $categoryIds[] = $category->id; // Include the parent category
    
                $query->whereIn('category_id', $categoryIds);
            }
        }
    
        // Apply filters
        if ($request->filled('brand')) {
            $brands = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $query->whereIn('brand_id', $brands);
        }
        if ($request->filled('processor')) {
            $processors = is_array($request->processor) ? $request->processor : explode(',', $request->processor);
            $query->whereIn('processor', $processors);
        }
        if ($request->filled('ram')) {
            $ramSizes = is_array($request->ram) ? array_map('intval', $request->ram) : [(int) $request->ram];
            $query->whereIn('ram_size', $ramSizes);
        }
        if ($request->filled('hdd')) {
            $hddSizes = is_array($request->hdd) ? array_map('intval', $request->hdd) : [(int) $request->hdd];
            $query->whereIn('storage', $hddSizes);
        }
        if ($request->filled('graphics_card')) {
            $graphicsCards = is_array($request->graphics_card) ? $request->graphics_card : explode(',', $request->graphics_card);
            $query->whereIn('graphics_card', $graphicsCards);
        }
        if ($request->filled('price') && is_array($request->price) && count($request->price) === 2) {
            [$min, $max] = $request->price;
            $query->whereBetween('price', [(float) $min, (float) $max]);
        }
        
        if ($request->filled('screen_size')) {
            $screenSizes = is_array($request->screen_size) ? array_map('intval', $request->screen_size) : [(int) $request->screen_size];
            $query->whereIn('screen_size', $screenSizes);
        }
        if ($request->filled('search')) {
            $searchTerms = explode(' ', $request->search); // Razbijamo pretragu na reči
        
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $searchTerm = '%' . $term . '%';
                    $q->orWhere('name', 'LIKE', $searchTerm)
                      ->orWhere('description', 'LIKE', $searchTerm)
                      ->orWhere('model', 'LIKE', $searchTerm)
                      ->orWhereHas('brand', function ($q) use ($searchTerm) { 
                          $q->where('name', 'LIKE', $searchTerm);
                      }) 
                      ->orWhereHas('category', function ($q) use ($searchTerm) { 
                          $q->where('name', 'LIKE', $searchTerm);
                      });
                }
            });
        }
        
        
        
    
        // Fetch products with brand
        $products = $query->with('brand')->get(); // Ensure brand is included
    
        return response()->json($products);
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
            'name' => 'required|string|max:255',
        ]);
    
        // Generate the slug
        $slug = strtolower(str_replace(' ', '-', $request->name));
       // Check if the slug already exists in the database
    if (Category::where('slug', $slug)->exists()) {
        return redirect()->back()->withErrors(['slug' => 'The slug already exists.']);
    }
    
        // Insert into the categories table
        DB::table('categories')->insert([
            'name' => $request->name,
            'slug' => $slug,  
            'parent_id' => $request->parent_id ?? null,
            'position' => $request->position ?? 0,
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
    
        // Generate the slug
        $slug = strtolower(str_replace(' ', '-', $request->name));
    
        // Ensure uniqueness of the slug (if needed)
        if (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            return redirect()->back()->withErrors(['slug' => 'The slug already exists.']);
        }
    
        // Update the category
        $category->update([
            'name' => $request->name,
            'slug' => $slug,  
            'parent_id' => $request->parent_id ?? null,
            'position' => $request->position ?? 0,
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
