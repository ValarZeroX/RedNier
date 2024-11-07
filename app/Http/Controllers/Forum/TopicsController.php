<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Repositories\TopicsRepository;

class TopicsController extends Controller
{
    protected $topicsRepository;

    public function __construct(TopicsRepository $topicsRepository)
    {
        $this->topicsRepository = $topicsRepository;
    }

    /**
     * Display a listing of the topics.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $communityId = $request->input('community_id');
        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);

        if (!$communityId) {
            return response()->json(['message' => 'community_id is required'], 400);
        }

        $topics = $this->topicsRepository->getTopicsByCommunityId($communityId, $perPage, $page);

        return response()->json($topics);
    }

    /**
     * Display the specified topic with related posts and comments.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $data = $this->topicsRepository->getTopicByIdWithPaginatedPosts($id, $perPage, $page);

        if (!$data) {
            return response()->json(['message' => 'Topic not found'], 500);
        }

        return response()->json($data);
    }
}