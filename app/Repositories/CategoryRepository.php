<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function getList($data)
    {
        $limit  = !empty($data['limit']) ? $data['limit'] : 10;

        return $this->category->latest()
            ->simplePaginate($limit);
    }

    public function getCategoryById($id)
    {
        return $this->category->with(['parent', 'children'])->where('id', $id)->first();
    }

    public function store($data)
    {
        return $this->category->create($data);
    }

    public function update($data, $id)
    {
        return $this->category
            ->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        $this->category->where('parent_id', $id)->update(['parent_id' => null]);

        return $this->category
            ->where('id', $id)
            ->delete();
    }
}
