<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\ProductCategories;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    protected $productService;
    protected $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            
            // if Request is ajax redirect with Data to datatable
            if ($request->ajax()) {
                return $this->productService->getAll($request);
            }
            // redirect to product view and compact with categories
            $categories = $this->categoryService->getAll();
            return view('product.index', compact('categories'));

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
            $this->productService->storeData($request);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->productService->deleteById($id);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return back();

    }
}
