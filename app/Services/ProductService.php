<?php

namespace App\Services;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Validator;

class ProductService
{

    protected $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($request)
    {
        return $this->productRepository->getAllProducts($request);
    }

    public function storeData($data) {
        // Validate Data

        $rules = [
            'name'          => 'required|string|max:191',
            'description'   => 'required|string|max:191',
            'price'         => 'required',
            'image'         => 'image|mimes:jpeg,bmp,png|max:5120' // 5Mb
        ];

        $validator = Validator::make($data->all(), $rules);
        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $this->productRepository->store($data);
    }

    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $product = $this->productRepository->destroy($id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete category data');
        }

        DB::commit();

        return $product;
    }
}
