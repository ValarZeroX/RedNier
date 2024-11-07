<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Redis;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        // 嘗試從 Redis 獲取資料
        $categories = Redis::get('categories');

        if (!$categories) {
            // 如果 Redis 中沒有，從資料庫查詢
            $categoriesData = Category::with('subCategories')->get()->map(function ($category) {
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

            // 將資料轉為 JSON 字串並存入 Redis（設定過期時間為一小時）
            $categoriesJson = $categoriesData->toJson();
            Redis::setex('categories', 3600, $categoriesJson);

            $categories = $categoriesJson;
        }

        // 將 JSON 字串轉回陣列
        $categories = json_decode($categories, true);

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
