<?php

namespace App\Repositories;

use App\Models\SubCategory;

class SubCategoryRepository
{
    public function getAll()
    {
        return SubCategory::all();
    }

    public function findById($id)
    {
        return SubCategory::findOrFail($id);
    }

    public function create(array $data)
    {
        return SubCategory::create($data);
    }

    public function update($id, array $data)
    {
        $subCategory = $this->findById($id);
        $subCategory->update($data);
        return $subCategory;
    }

    public function delete($id)
    {
        $subCategory = $this->findById($id);
        $subCategory->delete();
        return true;
    }
}
