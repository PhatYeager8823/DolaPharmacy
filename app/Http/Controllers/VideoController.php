<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video; // Nhớ tạo Model Video và khai báo fillable nhé

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::where('is_active', 1)->latest()->paginate(12);
        return view('video.index', compact('videos'));
    }
}
