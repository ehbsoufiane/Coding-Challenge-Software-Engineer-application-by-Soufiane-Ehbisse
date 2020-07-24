<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Http\File;
use App\Models\ProductCategories;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{

    protected $product;

    public function __construct(Product $product) {
        $this->product = $product;
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllProducts($request)
    {
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function store($data) {
        // Store Data in DB
        $product = new $this->product;
        $product->name          = $data->name;
        $product->description   = $data->description;
        $product->price         = $data->price;

        if($data->hasFile('image')) {
            $fullPath       = Storage::disk('public')->putFile('product/images/', new File($data->image));
            $product->image = basename($fullPath);
        }

        $product->save();

        // store categories related to each product
        if ($data->has('categories')) {
            foreach ($data->categories as $key => $category) {
                $productCategories              = new ProductCategories();
                $productCategories->product_id  = $product->id;
                $productCategories->category_id = $category;
        
                $productCategories->save();
            }
        }

        return $product->fresh();
    }


        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);

        // delete image related to this product
        Storage::disk('public')->delete('product/images/' .$product->image);

        // Delete Product
        // you can use softdelete for incentive Data
        $product->delete();
        
        return back();
    }
}
