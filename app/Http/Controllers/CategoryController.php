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
     * Display a listing of the resource for admin
     */

     public function index()
     {
         return Category::paginate(10); 
     }

    //returns view
     public function dasboardIndex()
     {
        $categories = Category::all();
         return view('categories.index', compact('categories'));

     }
    //data for modal
     public function show($id)
     {
         $category = Category::findOrFail($id); 
         return response()->json($category);
     }
     //data for displaying categories with children only 
     public function getHomepageIndex(){
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return response()->json([
            'data' => $categories,
        ]);
     }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        // Generate slug
        $slug = strtolower(str_replace(' ', '-', $request->name));

        if (Category::where('slug', $slug)->exists()) {
            return response()->json(['error' => 'The slug already exists.'], 422);
        }
    
        
        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,  
            'parent_id' => $request->parent_id ?? null,
            'position' => $request->position ?? 0,
        ]);
    
        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }
    
    public function update(Request $request, $id)
    {
        
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $slug = strtolower(str_replace(' ', '-', $request->name));
    
        if (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            return response()->json(['error' => 'The slug already exists.'], 422);
        }
    
        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id ?? null,
            'position' => $request->position ?? 0,
        ]);
    
        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
