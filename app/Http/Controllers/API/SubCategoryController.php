<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SubCategoryService;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    protected $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    public function index()
    {
        return response()->json($this->subCategoryService->getAllSubCategories());
    }

    public function show($id)
    {
        return response()->json($this->subCategoryService->getSubCategoryById($id));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            // Add other validation rules as needed
        ]);

        $subCategory = $this->subCategoryService->createSubCategory($validatedData);
        return response()->json($subCategory, 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            // Add other validation rules as needed
        ]);

        $subCategory = $this->subCategoryService->updateSubCategory($id, $validatedData);
        return response()->json($subCategory);
    }

    public function destroy($id)
    {
        $this->subCategoryService->deleteSubCategory($id);
        return response()->json(null, 204);
    }
}
