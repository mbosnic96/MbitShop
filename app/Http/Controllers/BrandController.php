<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Http;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource for admin
     */
    public function index()
    {
        return Brand::paginate(10); 
    }
    //returns view for dashboard 
    public function dasboardIndex()
    {
        return view('brands.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new brand using Eloquent
        $brand = Brand::create([
            'name' => trim($validatedData['name']),
        ]);

        // Return success message
        return response()->json([
            'message' => 'Brend uspešno dodat!',
            'brand' => $brand,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $brand = Brand::findOrFail($id); // Fetch the brand by ID
    return response()->json($brand); // Return the brand as JSON
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        $brand = Brand::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand->update([
            'name' => $request->name,
        ]);

        // Return success message
        return response()->json([
            'message' => 'Brend uspješno izmjenjen!',
            'brand' => $brand,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        // Return success message
        return response()->json([
            'message' => 'Brend uspješno obrisan!'
        ]);
    }
}
