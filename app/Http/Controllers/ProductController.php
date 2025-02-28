<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['brand', 'category'])->paginate(10);
        $brands = Brand::all();
        $categories = Category::all();
        return view('products.index', compact('products', 'brands','categories'));
    }

    
    public function show(Request $request, $slug)
    {
        try {
            $category = Category::where('slug', $slug)->with(['children'])->firstOrFail();
            $filterOptions = $this->initializeFilterOptions($category);
            $products = $this->getFilteredProducts($category, $request);
    
            return response()->json([
                'category' => $category,
                'filter_options' => $filterOptions,
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    private function initializeFilterOptions($category)
    {
        $baseQuery = Product::whereIn('category_id', $this->getCategoryIds($category));
    
        return [
            'brands' => Brand::whereHas('products', function($query) use ($category) {
                $query->whereIn('category_id', $this->getCategoryIds($category));
            })->get(),
            'processors' => $baseQuery->pluck('processor')->unique()->filter()->values(),
            'ram_sizes' => $baseQuery->pluck('ram_size')->unique()->filter()->values()->reject(fn($value) => is_null($value)),
            'storages' => $baseQuery->pluck('storage')->unique()->filter()->values()->reject(fn($value) => is_null($value)),
            'screen_sizes' => $baseQuery->pluck('screen_size')->unique()->filter()->values()->reject(fn($value) => is_null($value)),
            'graphics_cards' => $baseQuery->pluck('graphics_card')->unique()->filter()->values()->reject(fn($value) => is_null($value)),
        ];
    }
    

    private function getCategoryIds($category)
    {
        return $category->children->pluck('id')->push($category->id);
    }

    private function getFilteredProducts($category, $request)
    {
        return Product::whereIn('category_id', $this->getCategoryIds($category))
            ->when($request->selected_brands, fn($q) => $q->whereIn('brand_id', explode(',', $request->selected_brands)))
            ->when($request->selected_processors, fn($q) => $q->whereIn('processor', explode(',', $request->selected_processors)))
            ->when($request->selected_ram, fn($q) => $q->whereIn('ram_size', explode(',', $request->selected_ram)))
            ->when($request->selected_storage, fn($q) => $q->whereIn('storage', explode(',', $request->selected_storage)))
            ->when($request->selected_screen_sizes, fn($q) => $q->whereIn('screen_size', explode(',', $request->selected_screen_sizes)))
            ->when($request->selected_graphics, fn($q) => $q->whereIn('graphics_card', explode(',', $request->selected_graphics)))
            ->where('price', '<=', $request->max_price ?? 5000)
            ->with('brand')
            ->paginate($request->per_page ?? 15);
    }
    

    

    public function search(Request $request)
    {
        $query = Product::query();
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
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);
    
        $categoryId = $request->input('category');
        $brandId = $request->input('brand');
        $slug = strtolower(str_replace(' ', '-', $request->name));
        $slug .= '-' . Str::random(4); // Generates a random string of 4 characters

        
        // Handle image uploading
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $imagePaths[] = $file->store('products', 'local');
                } else {
                    return redirect()->back()->withErrors(['image' => 'One or more files are not valid.']);
                }
            }
        } else {
            // If no images are uploaded, set an empty array or null
            $imagePaths = [];
        }
    
        // Create product with default values for optional fields
        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description ?? '', // Set empty string if not provided
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'brand' => $request->brand ?? '', // Set empty string if not provided
            'model' => $request->model ?? '', // Set empty string if not provided
            'processor' => $request->processor ?? '', // Set empty string if not provided
            'ram_size' => $request->ram_size ?: null, // Set empty string if not provided
            'storage' => $request->storage ?: null,  // Set empty string if not provided
            'graphics_card' => $request->graphics_card ?? '', // Set empty string if not provided
            'operating_system' => $request->operating_system ?? '', // Set empty string if not provided
            'category' => $request->category ?? '', // Set empty string if not provided
            'image' => json_encode($imagePaths), // Store image paths or empty array
            'category_id' => $categoryId,
            'brand_id' => $brandId,
        ]);
    
        return redirect()->back()->with('message', 'Proizvod uspješno dodan!');
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);
    
        $categoryId = $request->input('category');
        $brandId = $request->input('brand');
        $slug = strtolower(str_replace(' ', '-', $request->name));
        $slug .= '-' . Str::random(4); // Generates a random string of 4 characters

        
        // Handle image uploading (optional)
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $imagePaths[] = $file->store('products', 'local');
                } else {
                    return redirect()->back()->withErrors(['image' => 'One or more files are not valid.']);
                }
            }
        } else {
            // If no new image, we retain the old image paths
            $product = Product::findOrFail($id);
            $imagePaths = json_decode($product->image, true); // Retain old image paths
        }
    
        // Perform the update
        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description ?? '', // Set empty string if not provided
            'price' => $request->price ?? 0, // Default to 0 if not provided
            'stock_quantity' => $request->stock_quantity ?? 0, // Default to 0 if not provided
            'brand' => $request->brand ?? '', // Set empty string if not provided
            'model' => $request->model ?? '', // Set empty string if not provided
            'processor' => $request->processor ?? '', // Set empty string if not provided
            'ram_size' => $request->ram_size ?: null, // Set to null if not provided or empty string
            'storage' => $request->storage ?: null, // Set to null if not provided or empty string
            'graphics_card' => $request->graphics_card ?? '', // Set empty string if not provided
            'operating_system' => $request->operating_system ?? '', // Set empty string if not provided
            'category' => $request->category ?? '', // Set empty string if not provided
            'image' => json_encode($imagePaths), // Store image paths or empty array
            'category_id' => $categoryId,
            'brand_id' => $brandId,
        ]);
   //    dd($request->all()); 
        
        return redirect()->back()->with('message', 'Proizvod uspješno dodan!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index');
    }
}
