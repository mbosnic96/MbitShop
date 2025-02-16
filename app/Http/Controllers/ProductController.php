<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    public function show($id)
{
    $product = Product::with(['brand', 'category'])->findOrFail($id);
    return view('categories.show', compact('products'));
}

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
