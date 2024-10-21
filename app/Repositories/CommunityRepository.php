<?php

namespace App\Repositories;

use App\Models\Community;

class CommunityRepository
{
    public function getAll()
    {
        return Community::all();
    }

    public function findById($id)
    {
        return Community::findOrFail($id);
    }
    
    public function update($id, array $data)
    {
        $community = $this->findById($id);
        $community->update($data);
        return $community;
    }

    public function delete($id)
    {
        $community = $this->findById($id);
        $community->delete();
        return true;
    }

    public function getBySubCategoryId($subCategoryId, $perPage = 15, $page)
    {
        return Community::where('sub_categories_id', $subCategoryId)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        return Community::create($data);
    }
}
