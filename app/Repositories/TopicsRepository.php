<?php

namespace App\Repositories;

use App\Models\Topic;
use Illuminate\Pagination\LengthAwarePaginator;

class TopicsRepository
{
    /**
     * 根據社群 ID 獲取主題，支持分頁。
     *
     * @param int $communityId
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getTopicsByCommunityId(int $communityId, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return Topic::where('community_id', $communityId)
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * 根據主題 ID 獲取主題及其相關的 Posts 和 Comments，並對 Posts 進行分頁。
     *
     * @param int $id
     * @param int $perPage
     * @param int $page
     * @return array|null
     */
    public function getTopicByIdWithPaginatedPosts(int $id, int $perPage = 10, int $page = 1): ?array
    {
        // 撈取 Topic 基本資訊、用戶和社群
        $topic = Topic::with(['user', 'community'])->find($id);

        if (!$topic) {
            return null;
        }

        // 撈取分頁的 Posts，包含每個 Post 的 Comments 和回復的 Comments，以及 Post 所屬的用戶
        $posts = $topic->posts()
                       ->with(['comments.replies', 'user'])
                       ->orderBy('created_at', 'desc')
                       ->paginate($perPage, ['*'], 'page', $page);

        return [
            'topic' => $topic,
            'posts' => $posts,
        ];
    }
}