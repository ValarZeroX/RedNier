<?php

namespace App\Services;

use App\Repositories\SubCategoryRepository;

class SubCategoryService
{
    protected $subCategoryRepository;

    public function __construct(SubCategoryRepository $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function getAllSubCategories()
    {
        return $this->subCategoryRepository->getAll();
    }

    public function getSubCategoryById($id)
    {
        return $this->subCategoryRepository->findById($id);
    }

    public function createSubCategory(array $data)
    {
        return $this->subCategoryRepository->create($data);
    }

    public function updateSubCategory($id, array $data)
    {
        return $this->subCategoryRepository->update($id, $data);
    }

    public function deleteSubCategory($id)
    {
        return $this->subCategoryRepository->delete($id);
    }
}
