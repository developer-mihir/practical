<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    public function index(Request $request)
    {
        try {
            $data = $request->all();
            $products = $this->productRepository->getList($data);

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Products have been retrieved successfully.',
                    'data'    => [
                        'products' => $products
                    ]
                ], 200);
        } catch (\Exception $e) {
            return response()
                ->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 201);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data      = $request->except('_method', 'token');

            $validator = Validator::make($data, [
                'name' => 'required|unique:products',
                'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->getMessageBag()->first(), 201);
            }

            $product = $this->productRepository->store($data);

            if (!empty($data['categories'])) {
                $product->categories()->attach($data['categories']);
            }

            DB::commit();

            if ($product) {
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Product has been created successfully.',
                        'data'    => [
                            'product' => $product,
                        ]
                    ], 200);
            }

            return response()
                ->json([
                    'success' => false,
                    'message' => 'Something went wrong please try again.',
                ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()
                ->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 201);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $product = $this->productRepository->getProductById($id);

            if ($product) {
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Product have been retrieved successfully.',
                        'data'    => [
                            'product' => $product
                        ]
                    ], 200);
            }

            return response()
                ->json([
                    'success' => false,
                    'message' => 'Product data not found.',
                ], 201);
        } catch (\Exception $e) {
            return response()
                ->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 201);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method');
            $validator = Validator::make($data, [
                'name' => 'required|unique:products,name,' . $id,
                'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->getMessageBag()->first(), 201);
            }

            $categories = [];

            if (!empty($data['categories'])) {
                $categories = $data['categories'];
                unset($data['categories']);
            }

            $product = $this->productRepository->update($data, $id);

            if (!empty($categories)) {
                $product = $this->productRepository->getProductById($id);
                $product->categories()->sync($categories);
            }

            DB::commit();

            if ($product) {
                $product = $this->productRepository->getProductById($id);
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Product has been updated successfully.',
                        'data'    => [
                            'product' => $product,
                        ]
                    ], 200);
            }

            return response()
                ->json([
                    'success' => false,
                    'message' => 'Something went wrong please try again.',
                ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()
                ->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 201);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $product = $this->productRepository->delete($id);
            DB::commit();

            if ($product) {
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Product been deleted successfully.',
                    ], 200);
            }

            return response()
                ->json([
                    'success' => false,
                    'code'    => 201,
                    'message' => 'Something went wrong please try again.',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()
                ->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 201);
        }
    }
}
