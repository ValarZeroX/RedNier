<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * 撈取所有 Posts，包含相關的 Topic 和嵌套 Comments
     */
    public function index()
    {
        // 使用 Eager Loading 撈取 Topics 和嵌套 Comments
        $posts = Post::with([
            'topic',
            'comments.replies' => function ($query) {
                $query->with('replies'); // 這樣可以撈取二級回復，如果需要更多層級可以繼續嵌套
            },
            // 您可以根據需要添加更多的關聯，例如用戶等
        ])->get();

        return response()->json($posts);
    }

    /**
     * 撈取單一 Post，包含相關的 Topic 和嵌套 Comments
     */
    public function show($id)
    {
        $post = Post::with([
            'topic',
            'comments.replies' => function ($query) {
                $query->with('replies'); // 支持多層次回復
            },
        ])->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post);
    }
}