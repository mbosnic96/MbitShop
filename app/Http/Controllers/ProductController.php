<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return Product::with(['brand:id,name', 'category:id,name'])->paginate(10);
    }

    //vraca proizvode na promociji (prvi slider u home)
    public function getPromoProduct()
    {
        $product = Product::where('promo', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($product);
    }
    //zadnje dodani
    public function latestProducts()
    {
        $product = Product::orderBy('created_at', 'desc')
        ->take(10)
        ->get();

    return response()->json($product);
    
    }

    //home view
    public function home()
    {    
        return view('components.welcome');
    }
    
      /**
     * Web view u dashboardu
     */
    public function dasboardIndex()
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('products.index', compact( 'brands','categories'));
    }

    //view za proizvode
    public function view()
    {
        return view('products.show');
    }

    //prikaz po slugu
    public function viewData($slug)
    {
        $product = Product::with(['brand:id,name', 'category:id,name'])->where('slug', $slug)->firstOrFail();
        return response()->json($product);
    }
    

    //podaci u modalu
    public function modalData($id)
    {
        $product = Product::with(['brand:id,name', 'category:id,name'])->findOrFail($id); // Fetch the brand by ID
        return response()->json($product); // Return the brand as JSON
    }
      /**
     * prikaz unutar kategorija + filteri
     */
    
    
    public function show(Request $request, $slug)
    {
        try {
            $category = Category::where('slug', $slug)->with(['children'])->firstOrFail();
            $filterOptions = $this->initializeFilterOptions($category);
            $products = $this->getFilteredProducts($category, $request);

            $products->each(function($product) {
                $product->price_with_discount = $product->price_with_discount; // Ovo je iz modela
            });
    
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
    
  /**
     * initialize filters
     */
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
    
  /**
     *  get category ids
     */
    private function getCategoryIds($category)
    {
        return $category->children->pluck('id')->push($category->id);
    }

      /**
     * get filtered products
     */
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
    

    
  /**
     * Search all products
     */
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
        
        
        $products = $query->with('brand')->get(); 
    
        return response()->json($products);
    }
    
     //najprodavaniji
    public function mostSoldProducts()
{
    $products = OrderItem::select('order_items.product_id', \DB::raw('SUM(order_items.quantity) as total_sold'))
        ->join('products', 'products.id', '=', 'order_items.product_id') 
        ->whereNotNull('products.stock_quantity')    ->whereNotNull('products.updated_at') // Ensure updated_at is not null
        ->where('products.updated_at', '>=', \Carbon\Carbon::now()->subMonth())
        ->groupBy('order_items.product_id')
        ->orderByDesc('total_sold')
        ->with('product') 
        ->paginate(10);

    return response()->json($products);
}

    
//na popustu
public function onDiscount()
{
    $products = Product::where('discount', '>', 0)
                                ->orderByDesc('discount') 
                                ->paginate(10);

    return response()->json($products);
}


    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'stock_quantity' => 'required|integer',
    ]);
    // Generate a unique slug
    $slug = strtolower(str_replace(' ', '-', $request->name));
    $slug .= '-' . Str::random(4); // Append a random string for uniqueness

    $productFolderName = $slug;
    $productFolderPath = "products/{$productFolderName}";

    // kreira folder za slike i sprema ih, te kao json u db
    if (!Storage::disk('public')->exists($productFolderPath)) {
        Storage::disk('public')->makeDirectory($productFolderPath);
    }

    
    $imagePaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            if ($file->isValid()) {
                // Keep the original file name
                $originalName = $file->getClientOriginalName();
                $imagePaths[] = $file->storeAs($productFolderPath, $originalName, 'public');
            } else {
                return response()->json([
                    'message' => 'One or more files are not valid.',
                ], 422); 
            }
        }
    }

    
    $product = Product::create([
        'name' => $request->name,
        'slug' => $slug,
        'description' => $request->description ?? '', 
        'price' => $request->price,
        'stock_quantity' => $request->stock_quantity,
        'brand' => $request->brand ?? '', 
        'model' => $request->model ?? '',
        'processor' => $request->processor ?? '', 
        'ram_size' => $request->ram_size ?: null, 
        'screen_size' => $request->screen_size ?: null, 
        'storage' => $request->storage ?: null,  
        'graphics_card' => $request->graphics_card ?? '', 
        'operating_system' => $request->operating_system ?? '', 
        'category' => $request->category ?? '', 
        'image' => json_encode($imagePaths), 
        'category_id' => $request->category,
        'brand_id' => $request->brand,
       'promo' => $request->has('promo'),
             'discount' => $request->discount ?? 0, 
    ]);

    return response()->json([
        'message' => 'Product created successfully!',
        'product' => $product,
    ], 201); 
}

    


    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, $id)
     {
         // Validate the request data
         $request->validate([
             'name' => 'required|string|max:255',
             'price' => 'required|numeric',
             'stock_quantity' => 'required|integer',
         ]);
     
         // Find the product
         $product = Product::findOrFail($id);
         $categoryId = $request->input('category');
         $brandId = $request->input('brand');
     
         
         $slug = strtolower(str_replace(' ', '-', $request->name));
         $slug .= '-' . Str::random(4);
         
         
         $productFolderName =$slug;
         $productFolderPath = "products/{$productFolderName}";
     
         
         if (!Storage::disk('public')->exists($productFolderPath)) {
             Storage::disk('public')->makeDirectory($productFolderPath);
         }
     
         $imagePaths = json_decode($product->image, true) ?? []; 

         if ($request->hasFile('images')) {     
             foreach ($request->file('images') as $file) {
                 if ($file->isValid()) {
                     $originalName = $file->getClientOriginalName();
                     
                     
                     $imagePaths[] = $file->storeAs($productFolderPath, $originalName, 'public');
                 } else {
                     return response()->json([
                         'message' => 'One or more files are not valid.',
                     ], 422);
                 }
             }
         }
     
         
         $product->update([
             'name' => $request->name,
             'slug' => $slug,
             'description' => $request->description ?? '',
             'price' => $request->price,
             'stock_quantity' => $request->stock_quantity,
             'brand' => $request->brand ?? '', 
             'model' => $request->model ?? '', 
             'processor' => $request->processor ?? '', 
             'ram_size' => $request->ram_size ?: null, 
            'screen_size' => $request->screen_size ?: null, 
             'storage' => $request->storage ?: null, 
             'graphics_card' => $request->graphics_card ?? '',
             'operating_system' => $request->operating_system ?? '', 
             'category' => $request->category ?? '', 
             'image' => json_encode($imagePaths), 
             'category_id' => $categoryId,
             'brand_id' => $brandId,
             'promo' => $request->has('promo'),
             'discount' => $request->discount ?? 0, 
         ]);
     
         // Return a JSON response
         return response()->json([
             'message' => 'Product updated successfully!',
             'product' => $product,
         ], 200); // HTTP 200: OK
     }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Proizvod uspješno obrisan!'
        ]);
    }
    //brise sliku

    public function deleteImage(Product $product, Request $request)
    {
        $request->validate([
            'image_path' => 'required|string'
        ]);
    
        $imagePath = $request->input('image_path');
    
        
        $currentImages = json_decode($product->image, true) ?? [];
    
        // Find the image in the array
        $imageIndex = array_search($imagePath, $currentImages);
    
        if ($imageIndex === false) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found in product images'
            ], 404);
        }
    
        // Remove the image from the array
        array_splice($currentImages, $imageIndex, 1);
    
        try {
            // Delete the physical file
            Storage::delete($imagePath);
    
            // Update the product's image field
            $product->image = count($currentImages) > 0 ? json_encode($currentImages) : null;
            $product->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
                'remaining_images' => $currentImages
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }
}
