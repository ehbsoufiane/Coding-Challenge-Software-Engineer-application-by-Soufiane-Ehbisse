<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{

    protected $category;

    public function __construct(Category $category) {
        $this->category = $category;
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllCategories()
    {
        return $this->category->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($data) {
        // Store Data in DB
        $category = new $this->category;
        $category->name = $data->name;
        $category->parent_category_id = $data->has('has_parent_category') ? $data->parent_id : NULL;

        $category->save();

        return $category->fresh();
    }


        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete Category
        // you can use softdelete for incentive Data
        $category = $this->category->find($id);

        foreach($category->subcategory()->get() as $subCategory){
            $subCategory->forcedelete();
        }
        $category->forcedelete();
        
        return back();
    }
}
