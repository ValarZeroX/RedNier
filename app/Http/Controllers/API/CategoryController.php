<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = Category::with('subCategories')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'subCategories' => $category->subCategories->map(function ($subCategory) {
                    return [
                        'id' => $subCategory->id,
                        'name' => $subCategory->name
                    ];
                })
            ];
        });

        return response()->json($categories);
    }

    public function show($id)
    {
        return response()->json($this->categoryService->getCategoryById($id));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        $category = $this->categoryService->createCategory($validatedData);
        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        $category = $this->categoryService->updateCategory($id, $validatedData);
        return response()->json($category);
    }

    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return response()->json(null, 204);
    }
}
