<?php

namespace App\Http\Controllers;

use App\Repositories\CommunityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CommunityController extends Controller
{
    protected $communityRepository;

    public function __construct(CommunityRepository $communityRepository)
    {
        $this->communityRepository = $communityRepository;
    }

    public function getBySubCategory(Request $request, $subCategoryId, $page)
    {
        $perPage = $request->input('per_page', 15);
        $communities = $this->communityRepository->getBySubCategoryId($subCategoryId, $perPage, $page);

        return response()->json($communities);
    }

    

    public function create(Request $request)
    {
        Log::info('Received data:', $request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sub_categories_id' => 'required|exists:sub_categories,id', // 確保這裡使用正確的欄位名
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $community = $this->communityRepository->create($request->all());

        return response()->json($community, 201);
    }

    /**
     * 根據社群 ID 撈取特定社群資料
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $community = $this->communityRepository->getById($id);

        if (!$community) {
            return response()->json(['message' => '社群未找到'], 404);
        }

        return response()->json($community);
    }
}
