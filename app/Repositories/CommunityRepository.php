<?php

namespace App\Repositories;

use App\Models\Community;

class CommunityRepository
{
    // public function getAll()
    // {
    //     return Community::all();
    // }

    // public function findById($id)
    // {
    //     return Community::findOrFail($id);
    // }
    
    // public function update($id, array $data)
    // {
    //     $community = $this->findById($id);
    //     $community->update($data);
    //     return $community;
    // }

    // public function delete($id)
    // {
    //     $community = $this->findById($id);
    //     $community->delete();
    //     return true;
    // }

    /**
     * 根據子分類 ID 撈取社群資料
     *
     * @param int $subCategoryId
     * @param int $perPage
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getBySubCategoryId(int $subCategoryId, int $perPage = 15, int $page = 1)
    {
        return Community::where('sub_categories_id', $subCategoryId)
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data): Community
    {
        return Community::create($data);
    }

    /**
     * 根據社群 ID 撈取社群資料
     *
     * @param int $id
     * @return Community|null
     */
    public function getById(int $id): ?Community
    {
        return Community::find($id);
    }
}
