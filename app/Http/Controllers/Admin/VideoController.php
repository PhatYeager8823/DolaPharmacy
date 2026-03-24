<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Nhớ import thư viện Str
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->paginate(10);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        // 1. Validate (Bỏ required ở thumbnail nếu muốn cho phép trống)
        $request->validate([
            'tieu_de' => 'required',
            'youtube_id' => 'required', // Link youtube
        ]);

        $data = $request->all();

        // 2. Xử lý Link Youtube để lấy ID TRƯỚC (Quan trọng)
        if ($request->has('youtube_id')) {
            $url = $request->youtube_id;
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            if (isset($params['v'])) {
                $data['youtube_id'] = $params['v'];
            } else {
                $path = parse_url($url, PHP_URL_PATH);
                $data['youtube_id'] = basename($path);
            }
        }

        // 3. Tự động tạo slug
        $slug = Str::slug($request->tieu_de);
        if (Video::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }
        $data['slug'] = $slug;

        // 4. Xử lý Ảnh (Ưu tiên ảnh upload -> Nếu không thì lấy ảnh Youtube)
        if ($request->hasFile('thumbnail')) {
            // --- TRƯỜNG HỢP 1: CÓ UPLOAD ẢNH ---
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/videos'), $filename);
            $data['thumbnail'] = $filename;
        } else {
            // --- TRƯỜNG HỢP 2: KHÔNG UPLOAD -> LẤY TỪ YOUTUBE ---
            try {
                // Link ảnh chất lượng cao nhất của Youtube
                $youtubeThumbUrl = "https://img.youtube.com/vi/" . $data['youtube_id'] . "/maxresdefault.jpg";

                // Đặt tên file
                $filename = time() . '_youtube_thumb.jpg';

                // Tải về và lưu vào thư mục public/images/videos
                $imageContent = file_get_contents($youtubeThumbUrl);

                if ($imageContent) {
                    file_put_contents(public_path('images/videos/' . $filename), $imageContent);
                    $data['thumbnail'] = $filename;
                }
            } catch (\Exception $e) {
                // Nếu lỗi (ví dụ video không có ảnh maxres), lấy ảnh chất lượng thường
                $youtubeThumbUrl = "https://img.youtube.com/vi/" . $data['youtube_id'] . "/hqdefault.jpg";
                $filename = time() . '_youtube_thumb.jpg';
                $imageContent = @file_get_contents($youtubeThumbUrl);
                if ($imageContent) {
                    file_put_contents(public_path('images/videos/' . $filename), $imageContent);
                    $data['thumbnail'] = $filename;
                }
            }
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Video::create($data);

        return redirect()->route('admin.videos.index')->with('success', 'Thêm video thành công');
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('admin.videos.edit', compact('video'));
    }

    // --- HÀM UPDATE (CẬP NHẬT) ---
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'youtube_id' => 'required|url',
        ]);

        $data = $request->all();

        // 1. Xử lý ID Youtube (Lấy ID mới từ form)
        $newYoutubeId = '';
        if ($request->has('youtube_id')) {
            $url = $request->youtube_id;
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            if (isset($params['v'])) {
                $newYoutubeId = $params['v'];
            } else {
                $path = parse_url($url, PHP_URL_PATH);
                $newYoutubeId = basename($path);
            }
            $data['youtube_id'] = $newYoutubeId;
        }

        // 2. Xử lý Ảnh
        // Logic: Ưu tiên Ảnh upload -> Nếu không thì check xem có cần lấy từ Youtube không -> Giữ nguyên
        if ($request->hasFile('thumbnail')) {
            // --- TRƯỜNG HỢP A: CÓ UPLOAD ẢNH MỚI ---

            // Xóa ảnh cũ
            if ($video->thumbnail && file_exists(public_path('images/videos/' . $video->thumbnail))) {
                unlink(public_path('images/videos/' . $video->thumbnail));
            }

            // Lưu ảnh mới
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/videos'), $filename);
            $data['thumbnail'] = $filename;

        } else {
            // --- TRƯỜNG HỢP B: KHÔNG UPLOAD ẢNH ---

            // Kiểm tra: Nếu người dùng đổi Link Youtube khác HOẶC tích vào ô "Tải lại ảnh"
            // Thì mới đi tải ảnh về. Còn không thì giữ nguyên ảnh cũ.
            if ($video->youtube_id !== $newYoutubeId || $request->has('refetch_youtube')) {

                // Xóa ảnh cũ trước (nếu có)
                if ($video->thumbnail && file_exists(public_path('images/videos/' . $video->thumbnail))) {
                    unlink(public_path('images/videos/' . $video->thumbnail));
                }

                // Tải ảnh mới từ Youtube
                try {
                    $youtubeThumbUrl = "https://img.youtube.com/vi/" . $newYoutubeId . "/maxresdefault.jpg";
                    $filename = time() . '_youtube_thumb.jpg';
                    $imageContent = @file_get_contents($youtubeThumbUrl);

                    // Fallback nếu không có ảnh maxres
                    if (!$imageContent) {
                        $youtubeThumbUrl = "https://img.youtube.com/vi/" . $newYoutubeId . "/hqdefault.jpg";
                        $imageContent = @file_get_contents($youtubeThumbUrl);
                    }

                    if ($imageContent) {
                        file_put_contents(public_path('images/videos/' . $filename), $imageContent);
                        $data['thumbnail'] = $filename;
                    }
                } catch (\Exception $e) {
                    // Nếu lỗi tải ảnh thì thôi, không làm gì (giữ nguyên hoặc null)
                }
            }
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Cập nhật slug nếu tiêu đề thay đổi (Tùy chọn, nếu muốn slug cố định thì bỏ đoạn này)
        if ($video->tieu_de != $request->tieu_de) {
            $slug = Str::slug($request->tieu_de);
            if (Video::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug . '-' . time();
            }
            $data['slug'] = $slug;
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('success', 'Cập nhật video thành công!');
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        if ($video->thumbnail && file_exists(public_path('images/videos/' . $video->thumbnail))) {
            unlink(public_path('images/videos/' . $video->thumbnail));
        }
        $video->delete();
        return back()->with('success', 'Đã xóa video.');
    }
}
