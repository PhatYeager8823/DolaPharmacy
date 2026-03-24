<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaiViet;

class BaiVietController extends Controller
{
    // 1. Danh sách tin tức
    public function index()
    {
        $posts = BaiViet::where('is_active', 1)->latest()->paginate(9);
        return view('baiviet.index', compact('posts'));
    }

    // 2. Chi tiết tin tức
    public function show($slug)
    {
        $post = BaiViet::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        // Gợi ý bài viết khác (trừ bài hiện tại)
        $relatedPosts = BaiViet::where('id', '!=', $post->id)
                               ->inRandomOrder()
                               ->take(4)
                               ->get();

        return view('baiviet.show', compact('post', 'relatedPosts'));
    }
}
