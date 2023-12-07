<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = DB::table('products')->get();
        return view('products.index', ['products'=> $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = DB::table('brands')->get();
        $categories = DB::table('categories')->get();
        return view('products.add-product', ['brands'=>$brands], ['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
        ]);

        DB::table('products')->insert([
            'name' => $request -> name,
            'description' => $request -> description,
            'price' => $request -> price,
            'stock_quantity' => $request -> stock_quantity,
            'brand' => $request -> brand,
            'model' => $request -> model,
            'processor' => $request -> processor,
            'ram_size' => $request -> ram_size,
            'storage' => $request -> storage,
            'graphics_card' => $request -> graphics_card,
            'operating_system' => $request -> operating_system,
            'category' => $request -> category,
            'image' => $request -> image,
        ]);
        return redirect()->route('products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
