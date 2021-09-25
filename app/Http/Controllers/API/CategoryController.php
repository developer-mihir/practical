<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function index(Request $request)
    {
        try {
            $data = $request->all();
            $categories = $this->categoryRepository->getList($data);

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Categories have been retrieved successfully.',
                    'data'    => [
                        'categories' => $categories
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
            $data      = $request->except('_method');
            $validator = Validator::make($data, [
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->getMessageBag()->first(), 201);
            }

            if (!empty($data['parent_id'])) {
                $parent_category = $this->categoryRepository->getCategoryById($data['parent_id']);

                if (!$parent_category) {
                    throw new \Exception('Parent category not found.', 201);
                }
            }

            $category = $this->categoryRepository->store($data);
            DB::commit();

            if ($category) {
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Category has been created successfully.',
                        'data'    => [
                            'category' => $category,
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
            $category = $this->categoryRepository->getCategoryById($id);

            if ($category) {
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Category have been retrieved successfully.',
                        'data'    => [
                            'category' => $category
                        ]
                    ], 200);
            }

            return response()
                ->json([
                    'success' => false,
                    'message' => 'Category data not found.',
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
                'name' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->getMessageBag()->first(), 201);
            }

            if (!empty($data['parent_id'])) {
                if ($data['parent_id'] == $id) {
                    throw new \Exception('You can not assign same category as parent category.', 201);
                }

                $parent_category = $this->categoryRepository->getCategoryById($data['parent_id']);

                if (!$parent_category) {
                    throw new \Exception('Parent category not found.', 201);
                }
            }

            $category = $this->categoryRepository->update($data, $id);
            DB::commit();

            if ($category) {
                $category = $this->categoryRepository->getCategoryById($id);
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Category has been updated successfully.',
                        'data'    => [
                            'category' => $category,
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

            $category = $this->categoryRepository->delete($id);
            DB::commit();

            if ($category) {
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Category been deleted successfully.',
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
}
