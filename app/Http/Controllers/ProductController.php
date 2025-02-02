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
        $products = Product::with(['brand', 'category'])->get();
        return view('products.index', compact('products'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('products.add-product', compact('brands', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
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
            return redirect()->back()->withErrors(['image' => 'No files uploaded']);
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'brand' => $request->brand,
            'model' => $request->model,
            'processor' => $request->processor,
            'ram_size' => $request->ram_size,
            'storage' => $request->storage,
            'graphics_card' => $request->graphics_card,
            'operating_system' => $request->operating_system,
            'category' => $request->category,
            'image' => json_encode($imagePaths),
            'category_id' => $categoryId,
            'brand_id' => $brandId,
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $brands = Brand::all();
        return view('products.edit', compact('product', 'brands'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
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
            // If no new image, we can leave the imagePaths empty, or just use old ones
            $product = Product::findOrFail($id);
            $imagePaths = json_decode($product->image); // Retain old image paths
        }

        // Perform the update
        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'brand' => $request->brand,
            'model' => $request->model,
            'processor' => $request->processor,
            'ram_size' => $request->ram_size,
            'storage' => $request->storage,
            'graphics_card' => $request->graphics_card,
            'operating_system' => $request->operating_system,
            'category' => $request->category,
            'image' => json_encode($imagePaths),
            'category_id' => $categoryId,
            'brand_id' => $brandId,
        ]);

        return redirect()->route('products.index');
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
