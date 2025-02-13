<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        $categories = Category::orderBy('name')->get()->groupBy('parent_id');
        
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
    public function show(Category $category)
    {
        // Eager load products for the category
        $category = $category->load('products');

        return view('categories.show', ['category' => $category]);
    }

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
