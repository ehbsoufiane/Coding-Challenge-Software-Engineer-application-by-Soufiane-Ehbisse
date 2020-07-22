<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Categories List
        $categories = Category::where('parent_category_id', NULL)->get();
        return view('category.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate Data
        $this->validate($request, [
            'name'     => 'required|string|max:191'
        ]);

        // Store Data
        $category = new Category();
        $category->name = $request->name;
        $category->parent_category_id = $request->has('has_parent_category') ? $request->parent_id : NULL;

        $category->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Check Data

        // Delete Category
        $category->delete();
        
        return back();
    }
}
