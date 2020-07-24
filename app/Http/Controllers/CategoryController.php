<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // Get Categories List and shere it with view
            $categories = $this->categoryService->getAll()->where('parent_category_id', NULL);
            return view('category.index', compact('categories'));
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->categoryService->storeData($request);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->categoryService->deleteById($id);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return back();
    }
}
