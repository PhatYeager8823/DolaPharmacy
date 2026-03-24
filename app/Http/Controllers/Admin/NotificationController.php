<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThongBao;
use App\Models\NguoiDung;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Hiển thị danh sách thông báo trong admin
     */
    public function index()
    {
        $notifications = ThongBao::with('nguoiDung')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Hiển thị form tạo thông báo mới
     */
    public function create()
    {
        $users = NguoiDung::orderBy('ten')->get();
        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Lưu thông báo mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'tieu_de'    => 'required|string|max:255',
            'noi_dung'   => 'required|string',
            'loai'       => 'required|in:don_hang,khuyen_mai,he_thong,bao_mat,thong_bao',
            'nguoi_nhan' => 'required|in:all,specific',
        ]);

        if ($request->nguoi_nhan === 'all') {
            // Gửi cho tất cả người dùng
            $users = NguoiDung::all();
            foreach ($users as $user) {
                ThongBao::create([
                    'nguoi_dung_id' => $user->id,
                    'tieu_de'       => $request->tieu_de,
                    'noi_dung'      => $request->noi_dung,
                    'loai'          => $request->loai,
                    'duong_dan'     => $request->duong_dan,
                    'da_xem'        => 0,
                ]);
            }
            $message = 'Đã gửi thông báo đến ' . $users->count() . ' người dùng.';
        } else {
            // Gửi cho người dùng cụ thể
            $request->validate([
                'nguoi_dung_id' => 'required|array|min:1',
                'nguoi_dung_id.*' => 'exists:users,id',
            ]);

            foreach ($request->nguoi_dung_id as $userId) {
                ThongBao::create([
                    'nguoi_dung_id' => $userId,
                    'tieu_de'       => $request->tieu_de,
                    'noi_dung'      => $request->noi_dung,
                    'loai'          => $request->loai,
                    'duong_dan'     => $request->duong_dan,
                    'da_xem'        => 0,
                ]);
            }
            $message = 'Đã gửi thông báo thành công.';
        }

        return redirect()->route('admin.notifications.index')->with('success', $message);
    }

    /**
     * Xóa thông báo
     */
    public function destroy($id)
    {
        $notification = ThongBao::findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Đã xóa thông báo.');
    }
}
