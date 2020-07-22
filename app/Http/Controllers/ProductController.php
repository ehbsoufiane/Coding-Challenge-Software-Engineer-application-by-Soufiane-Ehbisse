<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Models\ProductCategories;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if Request is ajax redirect with Data to datatable
        if ($request->ajax()) {
            $columns = [
                0  => 'id',
                1  => 'name',
                2  => 'description',
                3  => 'price',
                4  => 'image',
                5  => 'Actions'
            ];
            
            $order              = $columns[$request->input('order.0.column')];
            $dir                = $request->input('order.0.dir');
            $category_id        = $request->input('columns.0.search.value');

            $products = Product::when($category_id, function ($q) use ($category_id) {
                            $q->select('products.*', 'categories.id as categoryId')
                                ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                                ->leftJoin('categories', 'categories.id', '=', 'product_categories.category_id');
                            $q->where('categories.id', $category_id);
                        });
            
            $iTotalDisplayRecords = $products->get()->count();
            $products = $products->offset($request->start)
                                ->limit($request->length)
                                ->orderBy($order, $dir)
                                ->get();
            
            foreach ($products as $key => $product) {
                $temp_categories = [];
                foreach ($product->categories() as $key => $category) {
                    $category = [
                        'id' => $category->category->id,
                        'name' => $category->category->name,
                    ];
                    array_push($temp_categories, $category);
                }
                $product->categories = $temp_categories;
            }

            return response()->json([
                'draw'                 => $request->draw,
                'iTotalRecords'        => $iTotalDisplayRecords,
                'iTotalDisplayRecords' => $iTotalDisplayRecords,
                'recordsFiltered'      => $products->count(),
                // 'sEcho'                => 0,
                'sColumns'             => "",
                'aaData'               => $products,
                'col'                  => $request->input('order.0.column')
            ], 200);
        }

        // redirect to product view and compact with categories
        $categories = Category::all();
        return view('product.index', compact('categories'));
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
            'name'          => 'required|string|max:191',
            'description'   => 'required|string|max:191',
            'price'         => 'required',
            'image'         => 'image|mimes:jpeg,bmp,png|size:5120' // 5Mb
        ]);

        // store Data
        $product = new Product();
        $product->name          = $request->name;
        $product->description   = $request->description;
        $product->price         = $request->price;

        if($request->hasFile('image')) {
            $fullPath       = Storage::disk('public')->putFile('product/images/', new File($request->image));
            $product->image = basename($fullPath);
        }

        $product->save();

        // store categories related to each product
        if ($request->has('categories')) {
            foreach ($request->categories as $key => $category) {
                $productCategories              = new ProductCategories();
                $productCategories->product_id  = $product->id;
                $productCategories->category_id = $category;
        
                $productCategories->save();
            }
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // delete image related to this product
        Storage::disk('public')->delete('product/images/' .$product->image);

        // destroy categories related to this product


        // and finaly destroy product
        $product->delete();

        return back();
    }
}
