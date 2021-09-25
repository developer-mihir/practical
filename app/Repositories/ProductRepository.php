<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    protected $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function getList($data)
    {
        $limit  = !empty($data['limit']) ? $data['limit'] : 10;

        return $this->product->latest()
            ->simplePaginate($limit);
    }

    public function getProductById($id)
    {
        return $this->product->with('categories')->where('id', $id)->first();
    }

    public function store($data)
    {
        return $this->product->create($data);
    }

    public function update($data, $id)
    {
        return $this->product
            ->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->product
            ->where('id', $id)
            ->delete();
    }
}
